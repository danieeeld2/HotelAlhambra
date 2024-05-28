<?php 
require_once("html.php");
require_once("funcionesValidacion.php");
require_once("funcionesBD.php");
require_once("conexionBD.php");
require_once("gestionarReservas.php");

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
        } else {
            $_SESSION["error-login"] = "<p class='error'>La contraseña es incorrecta</p>";
        }
    }
}

// Comrpobamos si se ha enviado el formulario de logout
if (isset($_POST["cerrar-sesion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["rol"] = "Anonimo";
    $_SESSION["iniciado-sesion"] = false;
    unset($_SESSION["usuario"]);
    unset($_SESSION["datos-login"]);
}

///////////////////////////////////// GESTION DE REGISTRO ///////////////////////////////////////

// Comprobamos si se ha enviado el formulario de registro
if (isset($_POST["enviar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-registro"], $_SESSION["datos-registro"]] = validarDatosRegistro();
}
if (isset($_POST["confirmar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = insertarUsuario($conexion, $_SESSION["datos-registro"]);
    if($resultado) {
        $_SESSION["rol"] = "Cliente";
        $_SESSION["usuario"] = $_SESSION["datos-registro"]["nombre"];
        $_SESSION["email"] = $_SESSION["datos-registro"]["email"];
        $_SESSION["iniciado-sesion"] = true;
        $_GET["pagina"] = "inicio";
        unset($_SESSION["datos-registro"]);
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
    if($resultado) { // Borrar las fotos de la habitación en caso de que se haya eliminado correctamente
        $resultado_fotos = borrarFotosHabitacion($conexion, $habitacion[1]["Habitacion"]);
    }
}
// Comprobamos si se ha enviado el formulario de editar habitaciones (No gestiona la modificación de imágenes, eso va en un form a parte)
if(isset($_POST["modificar-habitacion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
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
    [$_SESSION["errores-reserva"], $_SESSION["datos-reserva"]] = validarDatosReserva();
    // Si todo esta correcto inciar el proceso de reserva
    if(empty($_SESSION["errores-reserva"])){
        // Primero eliminamos los que hayan excedido la marca temporal
        borrarReservasCaducadas($conexion);
        // Buscar reserva ópyima
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
            if($resultado){
                $_SESSION["tiempo-inicio-reserva"] = time();
                $_SESSION["reserva"] = true;
                $_SESSION["id-reserva"] = obtenerIdReserva($conexion, $habitacion, $email, $_SESSION["datos-reserva"]);
            }
        } else {
            $reserva = false;
        }
    }
}
// Comprobamos si se ha enviado el formulario de confirmar reserva
if(isset($_POST["confirmar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if(time() - $_SESSION["tiempo-inicio-reserva"] < 30){
        confirmarReserva($conexion, $_SESSION["id-reserva"]);
        if(isset($_SESSION["datos-reserva"])) unset($_SESSION["datos-reserva"]);
    } else {
        $expirada = true;
    }
    if(isset($_SESSION["reserva"])) unset($_SESSION["reserva"]);
    if(isset($_SESSION["tiempo-inicio-reserva"])) unset($_SESSION["tiempo-inicio-reserva"]);
    if(isset($_SESSION["id-reserva"])) unset($_SESSION["id-reserva"]);
}
// Comprobamos si se ha enviado el formulario de cancelar reserva
if(isset($_POST["cancelar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    borrarReserva($conexion, $_SESSION["id-reserva"]);
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
            if($_SESSION["rol"] == "Anonimo"){
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
                    HTML_formulario_reserva($_SESSION["usuarios"]);
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
        default:
            HTML_error_path();
            break;
    }
} else {
    HTML_pagina_inicio();
}


HTML_aside();
HTML_footer() ;
HTML_close();

// Desconectamos de la base de datos
desconectar_bbdd($conexion);
?>