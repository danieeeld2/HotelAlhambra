<?php 
require_once("html.php");
require_once("funcionesValidacion.php");
require_once("funcionesBD.php");
require_once("conexionBD.php");

session_start();

// Conectamos con la base de datos
$conexion = conectar_bbdd();

// Si no se ha iniciado sesión, se indica que el usuario esta como Anonimo
if (!isset($_SESSION["rol"])) {
    $_SESSION["rol"] = "Anonimo";
}
if (!isset($_SESSION["iniciado-sesion"])) {
    $_SESSION["iniciado-sesion"] = false;
}

// Nos encontramos por defecto en la pagina de inicio
if(!isset($_SESSION["ultima-pag-visitada"])){
    $_SESSION["ultima-pag-visitada"] = "inicio";
}

// Crear cookie para filtros de la reserva
if(!isset($_COOKIE["filtros-reserva"])){
    $valores_cookie = "3".","."antiguedad_asc".","."".","."".","."";
    setcookie("filtros-reserva", $valores_cookie, time() + (86400 * 30), "/");
}
// Crear cookie para filtros de listado de usuarios
if(!isset($_COOKIE["filtros-usuarios"])){
    $valores_cookie = "3".","."".","."".","."";
    setcookie("filtros-usuarios", $valores_cookie, time() + (86400 * 30), "/");
}
// Crear cookie para filtrado de los logs
if(!isset($_COOKIE["filtros-logs"])){
    $valores_cookie = "5".","."";
    setcookie("filtros-logs", $valores_cookie, time() + (86400 * 30), "/");
}

///////////////////////////////////// GESTION DE LOGIN ///////////////////////////////////////

// Comprobamos si se ha enviado el formulario de login
if (isset($_POST["iniciar-sesion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-login"], $_SESSION["datos-login"]] = validarLogIn($conexion);
    if(isset($_SESSION["error-login"])){
        unset($_SESSION["error-login"]);
    }
    if(empty($_SESSION["errores-login"])){
        [$resultado, $usuario] = getUsuario($conexion, $_SESSION["datos-login"]["email-sesion"], $_SESSION["datos-login"]["clave-sesion"]);
        if($resultado){
            // Logear al usuario y cambiar rol
            $_SESSION["rol"] = $usuario["rol"];
            $_SESSION["usuario"] = $usuario["nombre"];
            // Alamecnar email (que es un dato único) en la sesión
            $_SESSION["email"] = $_SESSION["datos-login"]["email-sesion"];
            $_SESSION["iniciado-sesion"] = true;
            $descripcion = "Inicio de sesión correcto con email " . $_SESSION["datos-login"]["email-sesion"];
            instertarLog($conexion, $descripcion, "Inicio de sesión");
        } else {
            $descripcion = "Intento de inicio de sesión fallido " . $_SESSION["datos-login"]["email-sesion"];
            instertarLog($conexion, $descripcion, "Fallo de inicio de sesión");
            $_SESSION["error-login"] = "<p class='error'>La contraseña es incorrecta</p>";
        }
    } else {
        $descripcion = "Intento de inicio de sesión fallido con email " . $_SESSION["datos-login"]["email-sesion"];
        instertarLog($conexion, $descripcion, "Fallo de inicio de sesión");
    }
}

// Comrpobamos si se ha enviado el formulario de logout
if (isset($_POST["cerrar-sesion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["rol"] = "Anonimo";
    $_SESSION["iniciado-sesion"] = false;
    $descripcion = "Cierre de sesión con email " . $_SESSION["email"];
    instertarLog($conexion, $descripcion, "Cierre de sesión");
    unset($_SESSION["usuario"]);
    unset($_SESSION["datos-login"]);
    // Reseteamos la cookie de filtros de usuario (por si se cambia de administrador a recepcionista)
    $valores_cookie = "3".","."".","."".","."";
    setcookie("filtros-usuarios", $valores_cookie, time() + (86400 * 30), "/");
    $_COOKIE["filtros-usuarios"] = $valores_cookie;
}

// Comprobamos si se ha enviado el formulario de cambio de datos del usuario
if(isset($_POST["cambiar-datos-usuario"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-datos-usuario"], $_SESSION["datos-usuario"]] = validarCambioDatos($conexion);
    if(empty($_SESSION["errores-datos-usuario"])){
        $id = getUsuarioID($conexion, $_SESSION["email"]);
        $descripcion = "Cambio de datos de usuario con email " . $_SESSION["email"];
        if(!empty($_SESSION["datos-usuario"]["email"])){
            actualizarEmail($conexion, $id, $_SESSION["datos-usuario"]["email"]);
            cambiarEmailReservas($conexion, $_SESSION["email"], $_SESSION["datos-usuario"]["email"]);
            $_SESSION["email"] = $_SESSION["datos-usuario"]["email"];
            $descripcion .= ". Ha cambiado el email a " . $_SESSION["datos-usuario"]["email"];
        }
        if(!empty($_SESSION["datos-usuario"]["tarjeta"])){
            actualizarTarjeta($conexion, $id, $_SESSION["datos-usuario"]["tarjeta"]);
            $descripcion .= ". Ha cambiado la tarjeta a " . $_SESSION["datos-usuario"]["tarjeta"];
        }
        if(!empty($_SESSION["datos-usuario"]["clave"])){
            actualizarClave($conexion, $id, $_SESSION["datos-usuario"]["clave"]);
            $descripcion .= ". Ha cambiado la contraseña";
        }
        unset($_SESSION["datos-usuario"]);
        $_SESSION["exito-cambio-datos-usuario"] = true;
        instertarLog($conexion, $descripcion, "Cambio de datos de usuario");
    }
}

///////////////////////////////////// GESTION DE REGISTRO ///////////////////////////////////////

// Comprobamos si se ha enviado el formulario de registro
if (isset($_POST["enviar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-registro"], $_SESSION["datos-registro"]] = validarDatosRegistro($conexion);
}
if (isset($_POST["confirmar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if($_SESSION["rol"] != "Administrador"){
        if(isset($_SESSION["modificar-usuario"]) && $_SESSION["modificar-usuario"]){
            borrarUsuario($conexion, $_SESSION["id-usuario"]);
            unset($_SESSION["id-usuario"]);
        }
        $resultado = insertarUsuario($conexion, $_SESSION["datos-registro"]);
        if($resultado) {
            if(!$_SESSION["iniciado-sesion"]){
                $_SESSION["rol"] = "Cliente";
                $_SESSION["usuario"] = $_SESSION["datos-registro"]["nombre"];
                $_SESSION["email"] = $_SESSION["datos-registro"]["email"];
                $_SESSION["iniciado-sesion"] = true;
                $_GET["pagina"] = "inicio";
            }
            if(!isset($_SESSION["modificar-usuario"])){
                $descripcion = "Registro de usuario correcto con email " . $_SESSION["datos-registro"]["email"];
                instertarLog($conexion, $descripcion, "Registro de usuario");
            } else {
                $descripcion = "Modificación de usuario correcta con email " . $_SESSION["datos-registro"]["email"];
                instertarLog($conexion, $descripcion, "Modificación de usuario");
            }
            if(isset($_SESSION["modificar-usuario"])) unset($_SESSION["modificar-usuario"]);
            unset($_SESSION["datos-registro"]);
        }
    } else {
        if(isset($_SESSION["modificar-usuario"]) && $_SESSION["modificar-usuario"]){
            borrarUsuario($conexion, $_SESSION["id-usuario"]);
            unset($_SESSION["id-usuario"]);
        }
        $resultado = insertarUsuarioRol($conexion, $_SESSION["datos-registro"], $_SESSION["datos-registro"]["rol"]);
        if($resultado){
            if(!isset($_SESSION["modificar-usuario"])){
                $descripcion = "Registro de usuario correcto con email " . $_SESSION["datos-registro"]["email"];
                instertarLog($conexion, $descripcion, "Registro de usuario");
            } else {
                $descripcion = "Modificación de usuario correcta con email " . $_SESSION["datos-registro"]["email"];
                instertarLog($conexion, $descripcion, "Modificación de usuario");
            }
            if(isset($_SESSION["modificar-usuario"])) unset($_SESSION["modificar-usuario"]);
            unset($_SESSION["datos-registro"]);
        }
    }
}

///////////////////////////////////// GESTION DE HABITACIONES ///////////////////////////////////////

// Comprobamos si se ha enviado el formulario de añadir habitaciones
if(isset($_POST["enviar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-habitacion"], $_SESSION["datos-habitacion"]] = validarDatosHabitaciones($conexion);
    if(isset($_SESSION["modificar-habitacion"])) unset($_SESSION["modificar-habitacion"]);
    if(isset($_SESSION["modificar-imagen-habitacion"])) unset($_SESSION["modificar-imagen-habitacion"]);
}
if(isset($_POST["confirmar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION["modificar-habitacion"])){
        $habitacion = getHabitacionID($conexion, $_SESSION["id-modificar"]);
        $resultado = borrarHabitacion($conexion, $_SESSION["id-modificar"]);
        $resultado_fotos = borrarFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
    }
    $resultado = insertarHabitacion($conexion, $_SESSION["datos-habitacion"]);
    if(!empty($_SESSION["datos-habitacion"]["fotos"]) && $resultado) {
        $fotos = $_SESSION["datos-habitacion"]["fotos"];
        foreach($fotos as $foto) {
            $resultado_foto = insertarFotoHabitacion($conexion, $foto, $_SESSION["datos-habitacion"]["habitacion"]);
        }
    }
    if($resultado) {
        if(isset($_SESSION["modificar-habitacion"]) || isset($_SESSION["modificar-imagen-habitacion"])){
            $descripcion = "Modificación de habitación correcta con nombre " . $_SESSION["datos-habitacion"]["habitacion"];
            instertarLog($conexion, $descripcion, "Modificación de habitación");
        } else {
            $descripcion = "Registro de habitación correcto con nombre " . $_SESSION["datos-habitacion"]["habitacion"];
            instertarLog($conexion, $descripcion, "Registro de habitación");
        }
        if(isset($_SESSION["datos-habitacion"])) unset($_SESSION["datos-habitacion"]);
    }
    if(isset($_SESSION["modificar-habitacion"])) unset($_SESSION["modificar-habitacion"]);
    if(isset($_SESSION["modificar-imagen-habitacion"])) unset($_SESSION["modificar-imagen-habitacion"]);
}
// Comprobamos si se ha enviado el formulario de eliminar habitaciones
if(isset($_POST["borrar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION["modificar-habitacion"])) unset($_SESSION["modificar-habitacion"]);
    if(isset($_SESSION["modificar-imagen-habitacion"])) unset($_SESSION["modificar-imagen-habitacion"]);
    if(isset($_SESSION["datos-habitacion"])) unset($_SESSION["datos-habitacion"]);
    $habitacion = getHabitacionID($conexion, $_POST["id-habitacion"]);
    $resultado = borrarHabitacion($conexion, $_POST["id-habitacion"]);
    if($resultado) { // Borrar las fotos de la habitación en caso de que se haya eliminado correctamente y las reservas asociadas
        $resultado_fotos = borrarFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
        $resultado_reservas = borrarReservasHabitacion($conexion, $habitacion[1]["Habitacion"]);
    }
    $descripcion = "Eliminación de habitación correcta con nombre " . $habitacion[1]["Habitacion"];
    instertarLog($conexion, $descripcion, "Eliminación de habitación");
}
// Comprobamos si se ha enviado el formulario de editar habitaciones (No gestiona la modificación de imágenes, eso va en un form a parte)
if(isset($_POST["modificar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if($_GET["pagina"] == "gestion-reservas-opcional"){
        $_GET["pagina"] = "gestion-habitaciones";
        $_SESSION["ultima-pag-visitada"] = "gestion-habitaciones";
        // Modificar el query string para que se muestre la página de gestión de habitaciones
        header("Location: index.php?pagina=gestion-habitaciones");
    }
    if(isset($_SESSION["datos-habitacion"])) unset($_SESSION["datos-habitacion"]);
    $habitacion = getHabitacionID($conexion, $_POST["id-habitacion"]);
    // Bindeamos los datos de la habitación a la variable de sesión
    $_SESSION["datos-habitacion"]["habitacion"] = $habitacion[1]["Habitacion"];
    $_SESSION["datos-habitacion"]["capacidad"] = $habitacion[1]["Capacidad"];
    $_SESSION["datos-habitacion"]["precio"] = convertirADecimal($habitacion[1]["Precio"]);
    $_SESSION["datos-habitacion"]["descripcion"] = $habitacion[1]["Descripcion"];
    // Obtenemos las fotos de la habitación
    [$resultado, $fotos] = getFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
    // Convertir a array de fotos
    if($resultado){
        foreach($fotos as $foto){
            $imagen = $foto["Imagen"];
            $_SESSION["datos-habitacion"]["fotos"][] = $imagen;
        }
    }
    $_SESSION["modificar-habitacion"] = true;
    if(isset($_SESSION["modificar-imagen-habitacion"])) unset($_SESSION["modificar-imagen-habitacion"]);
    if(isset($_POST["id-habitacion"])) $_SESSION["id-modificar"] = $_POST["id-habitacion"];
}
if(isset($_POST["enviar-modificar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
    [$_SESSION["errores-habitacion"], $_SESSION["datos-habitacion"]] = validarDatosHabitaciones($conexion);
}
// Comprobamos si se ha mandado el formulario de edición de imagenes
if(isset($_POST["modificar-imagenes-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if($_GET["pagina"] == "gestion-reservas-opcional"){
        $_GET["pagina"] = "gestion-habitaciones";
        $_SESSION["ultima-pag-visitada"] = "gestion-habitaciones";
        // Modificar el query string para que se muestre la página de gestión de habitaciones
        header("Location: index.php?pagina=gestion-habitaciones");
    }
    if(isset($_POST["id-habitacion"])) $_SESSION["id-modificar"] = $_POST["id-habitacion"];
    if(isset($_SESSION["datos-habitacion"])) unset($_SESSION["datos-habitacion"]);
    $habitacion = getHabitacionID($conexion, $_SESSION["id-modificar"]);
    // Bindeamos los datos de la habitación a la variable de sesión
    $_SESSION["datos-habitacion"]["habitacion"] = $habitacion[1]["Habitacion"];
    // Obtenemos las fotos de la habitación
    [$resultado, $_SESSION["fotos"]] = getFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
    $_SESSION["modificar-imagen-habitacion"] = true;
    if(isset($_SESSION["modificar-habitacion"])) unset($_SESSION["modificar-habitacion"]);
}
// Procesar subida de fotos
if(isset($_POST["enviar-fotos"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $habitacion = getHabitacionID($conexion, $_SESSION["id-modificar"]);
    $fotos = validarFotos();
    if($fotos) {
        foreach($fotos as $foto) {
            $resultado = insertarFotoHabitacion($conexion, $foto, $habitacion[1]["Habitacion"]);
        }
    }
    [$resultado, $_SESSION["fotos"]] = getFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
}
// Comprobamos si se ha enviado el formulario de eliminar fotos
if(isset($_POST["borrar-foto"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = borrarFotoID($conexion, $_POST["id-foto"]);
    // Cogemos el nombre de la haiotacion a la que pertenece la foto
    [$resultado, $habitacion] = getHabitacionID($conexion, $_SESSION["id-modificar"]);
    [$resultado, $_SESSION["fotos"]] = getFotosHabitacion($conexion, $habitacion["Habitacion"]);
}
// Comprobamos si se solicita salir del modo edición de imagenes
if(isset($_POST["salir-edicion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_SESSION["modificar-imagen-habitacion"])) unset($_SESSION["modificar-imagen-habitacion"]);
    if(isset($_SESSION["fotos"])) unset($_SESSION["fotos"]);
    if(isset($_SESSION["datos-habitacion"])) unset($_SESSION["datos-habitacion"]);
}

///////////////////////////////////// GESTION DE RESERVAS ///////////////////////////////////////

// Comprobamos si se ha enviado el formulario de reserva
if(isset($_POST["enviar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    // Modo reserva
    if(!isset($_POST["reforma"])){
        [$_SESSION["errores-reserva"], $_SESSION["datos-reserva"]] = validarDatosReserva();
        // Si todo esta correcto inciar el proceso de reserva
        if(empty($_SESSION["errores-reserva"])){
            // Primero eliminamos los que hayan excedido la marca temporal
            borrarReservasCaducadas($conexion);
            // Buscar reserva óptima
            [$ok, $habitacion] = comprobarReserva($conexion, $_SESSION["datos-reserva"]["numeropersonas"], $_SESSION["datos-reserva"]["entrada"], $_SESSION["datos-reserva"]["salida"]);
            if($ok){
                // Crear tupla de reserva con estado "pendiente", marca de tiempo actual y los datos del formulario
                if($_SESSION["rol"] == "Cliente"){
                    $email = $_SESSION["email"];
                } else {
                    $email = $_POST["usuario-reserva"];
                }
                $_SESSION["datos-reserva"]["email"] = $email;
                $resultado = crearReservaPendiente($conexion, $habitacion, $email, $_SESSION["datos-reserva"]);
                $descripcion = "Reserva de habitación pendiente con email " . $email;
                instertarLog($conexion, $descripcion, "Reserva de habitación Pendiente");
                if($resultado){
                    $_SESSION["tiempo-inicio-reserva"] = time();
                    $_SESSION["reserva"] = true;
                    $_SESSION["id-reserva"] = obtenerIdReserva($conexion, $habitacion, $email, $_SESSION["datos-reserva"]);
                }
            } else {
                $reserva = false;
            }
        }
    } else { // Modo reforma
        [$_SESSION["errores-reserva"], $_SESSION["datos-reserva"]] = validarDatosReforma();
        if(empty($_SESSION["errores-reserva"])){
            // Primero eliminamos los que hayan excedido la marca temporal
            borrarReservasCaducadas($conexion);
            $disponible = comprobarDisponibilidad($conexion, $_SESSION["datos-reserva"]["habitacion-reforma"], $_SESSION["datos-reserva"]["entrada"], $_SESSION["datos-reserva"]["salida"]);
            if(!$disponible){
                $mantenimiento = false;
            } else {
                $mantenimiento = true;
                // Crear la reserva de reforma
                $ok = establecerHabitacionReforma($conexion, $_SESSION["datos-reserva"]["habitacion-reforma"], $_SESSION["datos-reserva"]["entrada"], $_SESSION["datos-reserva"]["salida"]);
                if($ok){
                    $mantenimiento_confirmado = true;
                    $descripcion = "Habitación " . $_SESSION["datos-reserva"]["habitacion-reforma"] . " pendiente de reforma";
                    instertarLog($conexion, $descripcion, "Habitacion Pendiente de Reforma");
                } else {
                    $mantenimiento_confirmado = false;
                }
            }
            if(isset($_SESSION["datos-reserva"])) unset($_SESSION["datos-reserva"]);
        }
    }
}
// Comprobamos si se ha enviado el formulario de confirmar reserva
if(isset($_POST["confirmar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if(time() - $_SESSION["tiempo-inicio-reserva"] < 30){
        confirmarReserva($conexion, $_SESSION["id-reserva"]);
        $descripcion = "Reserva de habitación confirmada con email " . $_SESSION["datos-reserva"]["email"];
        instertarLog($conexion, $descripcion, "Reserva de habitación Confirmada");
        if(isset($_SESSION["datos-reserva"])) unset($_SESSION["datos-reserva"]);
    } else {
        $expirada = true;
        $descripcion = "Reserva de habitación caducada con email " . $_SESSION["datos-reserva"]["email"];
        instertarLog($conexion, $descripcion, "Reserva de habitación Caducada");
    }
    if(isset($_SESSION["reserva"])) unset($_SESSION["reserva"]);
    if(isset($_SESSION["tiempo-inicio-reserva"])) unset($_SESSION["tiempo-inicio-reserva"]);
    if(isset($_SESSION["id-reserva"])) unset($_SESSION["id-reserva"]);
}
// Comprobamos si se ha enviado el formulario de cancelar reserva
if(isset($_POST["cancelar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    borrarReserva($conexion, $_SESSION["id-reserva"]);
    $descripcion = "Reserva de habitación cancelada con email " . $_SESSION["datos-reserva"]["email"];
    instertarLog($conexion, $descripcion, "Reserva de habitación Cancelada");
    if(isset($_SESSION["reserva"])) unset($_SESSION["reserva"]);
    if(isset($_SESSION["tiempo-inicio-reserva"])) unset($_SESSION["tiempo-inicio-reserva"]);
    if(isset($_SESSION["id-reserva"])) unset($_SESSION["id-reserva"]);
}
// Comrpobamos si ha pasado el tiempo de confirmar reserva (Por si recarga la página)
if((isset($_SESSION["reserva"]) && $_SESSION["reserva"] == true)){
    if(time() - $_SESSION["tiempo-inicio-reserva"] > 30){;
        if(isset($_SESSION["reserva"])) unset($_SESSION["reserva"]);
        if(isset($_SESSION["tiempo-inicio-reserva"])) unset($_SESSION["tiempo-inicio-reserva"]);
        if(isset($_SESSION["id-reserva"])) unset($_SESSION["id-reserva"]);
        if(!isset($_POST["cancelar-reserva"])){
            $expirada = true;
        }
    }
}

///////////////////////////////////// GESTION DE LISTA DE RESERVAS ///////////////////////////////////////
// Comprobamos si se ha enviado el formulario de filtrado de reservas
if(isset($_POST["filtros-reservas"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = validarFormularioFiltro($conexion);
    $valores_cookie = $datos["paginacion"].",".$datos["ordenamiento"].",".$datos["fecha_inicio"].",".$datos["fecha_fin"].",".$datos["comentario"];
    setcookie("filtros-reserva", $valores_cookie, time() + (86400 * 30), "/");
    $_COOKIE["filtros-reserva"] = $valores_cookie;    
}
// Comprobamos si se ha enviado el formulario de cancelar reserva
if(isset($_POST["borrar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    borrarReserva($conexion, $_POST["id-reserva"]);
    $descripcion = "Reserva de habitación cancelada con id " . $_POST["id-reserva"];
    instertarLog($conexion, $descripcion, "Reserva de habitación Cancelada");
}
// Comprobar si se ha enviado el formulario de cambiar comentario de reserva
if(isset($_POST["modificar-comentario"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $comentario = "";
    if(!empty($_POST["nuevo-comentario"])){
        $comentario = checkInyection($_POST["nuevo-comentario"]);
    }
    $resultado = modificarComentario($conexion, $_POST["id-reserva"], $comentario);
    if($resultado){
        $descripcion = "Modificación de comentario de reserva con id " . $_POST["id-reserva"];
        instertarLog($conexion, $descripcion, "Modificación de comentario de reserva");
    }
}

///////////////////////////////////// GESTION DE LISTA DE USUARIOS ///////////////////////////////////////
// Comprobamos si se ha enviado el formulario de filtrado de usuarios
if(isset($_POST["filtros-usuarios"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = validarFiltroUsuarios();
    $valores_cookie = $datos["paginacion"].",".$datos["dni"].",".$datos["email"].",".$datos["rol"];
    setcookie("filtros-usuarios", $valores_cookie, time() + (86400 * 30), "/");
    $_COOKIE["filtros-usuarios"] = $valores_cookie;    
}
// Comprobamos si se ha enviado el formulario de eliminar usuario
if(isset($_POST["borrar-usuario"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $email = obtenerEmailUsuarioID($conexion, $_POST["id-usuario"]);
    $resultado = borrarUsuario($conexion, $_POST["id-usuario"]);
    if($resultado){
        borrarReservasUsuarioEmail($conexion, $email);
        $descripcion = "Eliminación de usuario con email " . $email;
        instertarLog($conexion, $descripcion, "Eliminación de usuario");
    }
}
if(isset($_POST["modificar-usuario"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario = getUsuariobyID($conexion, $_POST["id-usuario"]);
    $_SESSION["id-usuario"] = $_POST["id-usuario"];
    if($usuario[0]){
        $usuario = $usuario[1];
        $_SESSION["datos-registro"]["nombre"] = $usuario["nombre"];
        $_SESSION["datos-registro"]["apellidos"] = $usuario["apellidos"];
        $_SESSION["datos-registro"]["email"] = $usuario["email"];
        $_SESSION["datos-registro"]["dni"] = $usuario["dni"];
        $_SESSION["datos-registro"]["tarjeta"] = $usuario["tarjeta"];
        $_SESSION["datos-registro"]["rol"] = $usuario["rol"];
        $_SESSION["modificar-usuario"] = true;
        // Nota: La contraseña está hasheada, por lo que no se puede recuperar. Se introduciría una nueva en su lugar y se 
        // informaría al usuario de que se ha cambiado la contraseña por si quiere cambiarla
        $_GET["pagina"] = "registro";
        // Para que no limpie el formualrio al cambiar de página
        $_SESSION["ultima-pag-visitada"] = "registro";
    }
}


///////////////////////////////////// GESTION DE LISTA DE LOGS ///////////////////////////////////////
// Comprobamos si se ha enviado el formulario de filtrado de logs
if(isset($_POST["filtros-logs"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = validarFiltroLogs();
    $valores_cookie = $datos["paginacion"].",".$datos["tipo"];
    setcookie("filtros-logs", $valores_cookie, time() + (86400 * 30), "/");
    $_COOKIE["filtros-logs"] = $valores_cookie;    
}

///////////////////////////////////// GESTION DE BASE DE DATOS ///////////////////////////////////////
// Comprobamos si se ha enviado el formulario de crear backup base de datos
if(isset($_POST["crear-backup"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = crearBackup($conexion);
    if($resultado){
        $descripcion = "Creación de backup de la base de datos";
        instertarLog($conexion, $descripcion, "Creación de backup");
        $backup = true;
        // Encabezado para descargar el archivo
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="backup.sql"');

        // Imprimimos el archivo
        echo $resultado['backup_content'];

        // Si no lo pongo descarga HTML también
        exit();
    } else {
        $backup = false;
    }
}
// Comprobamos si se ha enviado el formulario de reiniciar la base de datos
if(isset($_POST["reiniciar-bd"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $reiniciarBD = reiniciarBD($conexion);
}
// Comprobamos que se ha recibido el forulario de restaurar backup
if(isset($_POST["restaurar-backup"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-backup"], $filename] = validarArchivoSQL();
    if(empty($_SESSION["errores-backup"])){
        $resultado = restaurarBackup($conexion, $filename);
        if($resultado){
            $restaurado = true;
        } else {
            $restaurado = false;
        }
    }
}

HTML_init();
HTML_header();
HTML_nav();
HTML_main_container() ;

// Gestion del contenido a generar en funcion de la pagina solicitada
if(isset($_GET["pagina"])) {
    // Limpiar variables de sesión asociada a formularios al cambiar de pagina
    if($_GET["pagina"] != $_SESSION["ultima-pag-visitada"]){
        limpiarSesionFormularios();
    }
    $_SESSION["ultima-pag-visitada"] = $_GET["pagina"];

    switch($_GET["pagina"]) {
        case "inicio":
            HTML_pagina_inicio();
            break;
        case "habitaciones":
            HTML_pagina_habitaciones($conexion);
            break;
        case "servicios":
            HTML_pagina_servicios();
            break;
        case "registro":
            if($_SESSION["rol"] != "Cliente"){
                HTML_form_registro() ;
            } else {
                HTML_error_permisos();
            }
            break;
        case "gestion-habitaciones":
            if($_SESSION["rol"] != "Recepcionista"){
                HTML_error_permisos();
            } else {
                HTML_tabla_Habitaciones($conexion);
                if(isset($_SESSION["modificar-imagen-habitacion"])){
                    HTML_editar_fotos_habitacion();
                    HTML_salir_edicion();
                } else {
                    HTML_form_habitaciones();
                }
            }
            break;
        case "reservas":
            if($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Cliente"){
                if(!isset($_SESSION["reserva"]) || $_SESSION["reserva"] == false){
                    $_SESSION["usuarios"] = getUsuarios($conexion);
                    $_SESSION["habitaciones"] = getHabitaciones($conexion)[1];
                    HTML_formulario_reserva($_SESSION["usuarios"], $_SESSION["habitaciones"]);
                    if(isset($mantenimiento)){
                        if($mantenimiento){
                            if($mantenimiento_confirmado){
                                HTML_success_mantenimiento_confirmado();
                            } else {
                                HTML_error_mantenimiento_confirmado();
                            }
                        } else {
                            HTML_error_mantenimiento();
                        }
                    }
                    if(isset($reserva)){
                        HTML_error_reserva();
                    }
                    if(isset($expirada)){
                        HTML_error_reserva_expirada();
                    }
                } else {
                    HTML_confirmar_reserva(obtenerDatosReserva($conexion, $_SESSION["id-reserva"]), $_SESSION["datos-reserva"]["email"]);
                }
            } else {
                HTML_error_permisos();
            }
            break;
        case "lista-reservas":
            if($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Cliente"){
                HTML_gestion_reservas($conexion);
            } else {
                HTML_error_permisos();
            }
            break;
        case "lista-usuarios":
            if($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Administrador"){
                HTML_gestion_usuarios($conexion);
            } else {
                HTML_error_permisos();
            }
            break;
        case "lista-logs":
            if($_SESSION["rol"] == "Administrador"){
                HTML_gestion_logs($conexion);
            } else {
                HTML_error_permisos();
            }
            break;
        case "gestion-bd":
            if($_SESSION["rol"] == "Administrador"){
                HTML_gestion_BD();
                if(isset($backup)){
                    if($backup){
                        HTML_success_backup();
                    } else {
                        HTML_error_backup();
                    }
                }
                if(isset($reiniciarBD)){
                    if($reiniciarBD){
                        HTML_success_reiniciarBD();
                    } else {
                        HTML_error_reiniciarBD();
                    }
                }
                if(isset($restaurado)){
                    if($restaurado){
                        HTML_success_restaurar();
                    } else {
                        HTML_error_restaurar();
                    }
                }
            } else {
                HTML_error_permisos();
            }
            break;
        case "gestion-reservas-opcional":
            if($_SESSION["rol"] == "Recepcionista"){
                HTML_tabla_opcional_reservas($conexion);
            } else {
                HTML_error_permisos();
            }
            break;
        default:
            HTML_error_path();
            break;
    }
} else {
    HTML_pagina_inicio();
}


HTML_aside($conexion);
HTML_footer() ;
HTML_close();

// Desconectamos de la base de datos
desconectar_bbdd($conexion);
?>