<?php 
require_once("funcionesAuxiliares.php");
require_once("funcionesBD.php");
// Función para validar los datos del formulario de registro
function validarDatosRegistro($conexion){
    // Inicializamos los arrays de errores y datos
    $errores = array();
    $datos = array();

    // Comprobar si se ha enviado el formulario
    if(isset($_POST["enviar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobar que el nombre no es vacio
        if(empty($_POST["nombre"])){
            $errores["nombre"] = "<p class='error'>El nombre no puede estar vacío</p>";
            $datos["nombre"] = "";
        }else{
            $_POST["nombre"] = checkInyection($_POST["nombre"]);
            // Comprobar que el nombre empieza por mayúscula
            if(!preg_match("/^[A-Z][a-z]+$/", $_POST["nombre"])){
                $errores["nombre"] = "<p class='error'>El nombre debe empezar por mayúscula y contener solo letras</p>";
                $datos["nombre"] = "";
            }else{
                $datos["nombre"] = $_POST["nombre"];
            }
        }

        // Los apellidos no pueden estar vacíos
        if(empty($_POST["apellidos"])){
            $errores["apellidos"] = "<p class='error'>Los apellidos no pueden estar vacíos</p>";
            $datos["apellidos"] = "";
        }else{
            $_POST["apellidos"] = checkInyection($_POST["apellidos"]);
            $datos["apellidos"] = $_POST["apellidos"];
        }

        // Comprobar que el DNI no está vacío
        if(empty($_POST["dni"])){
            $errores["dni"] = "<p class='error'>El DNI no puede estar vacío</p>";
            $datos["dni"] = "";
        }else{
            // Comprobar que está formado por 8 números y una letra
            if(!preg_match("/^[0-9]{8}[A-Z]$/", $_POST["dni"])){
                $errores["dni"] = "<p class='error'>El DNI debe contener 8 números y una letra</p>";
                $datos["dni"] = "";
            }else{
                // Comprobar que la letra es valida
                if(!validarLetraDNI($_POST["dni"])){
                    $errores["dni"] = "<p class='error'>La letra del DNI no es correcta</p>";
                    $datos["dni"] = "";
                }else{
                    $datos["dni"] = $_POST["dni"]; 
                }
            }
        }

        // Comprobar que el email no está vacío
        if(empty($_POST["email"])){
            $errores["email"] = "<p class='error'>El email no puede estar vacío</p>";
            $datos["email"] = "";
        }else{
            // Comprobar que el email es válido
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $errores["email"] = "<p class='error'>El email no es válido</p>";
                $datos["email"] = "";
            }else{
                if(!checkEmail($conexion, $_POST["email"]) || isset($_SESSION["modificar-usuario"])){
                    $datos["email"] = $_POST["email"];
                } else {
                    $errores["email"] = "<p class='error'>Ya existe una cuenta con este email</p>";
                    $datos["email"] = "";
                }
            }
        }

        // Comprobar que la clave no está vacía
        if(empty($_POST["clave"])){
            $errores["clave"] = "<p class='error'>La clave no puede estar vacía</p>";
            $datos["clave"] = "";
            $datos["clave-repetida"] = "";
        }else{
            $_POST["clave"] = checkInyection($_POST["clave"]);
            // Comprobar que la clave tiene al menos 5 caracteres
            if(strlen($_POST["clave"]) < 5){
                $errores["clave"] = "<p class='error'>La clave debe tener al menos 5 caracteres</p>";
                $datos["clave"] = "";
                $datos["clave-repetida"] = "";
            }else{
                $datos["clave"] = $_POST["clave"];
                // Comprobar que la clave repetida coincide
                if($_POST["clave"] != $_POST["clave-repetida"]){
                    $errores["clave-repetida"] = "<p class='error'>Las claves no coinciden</p>";
                    $datos["clave-repetida"] = "";
                } else {
                    $datos["clave-repetida"] = $_POST["clave-repetida"];
                }
            }
        }

        // Comprobar que la tarjeta no está vacía
        if(empty($_POST["tarjeta"])){
            $errores["tarjeta"] = "<p class='error'>La tarjeta no puede estar vacía</p>";
            $datos["tarjeta"] = "";
        }else{
            // Comprobar que la tarjeta tiene 16 dígitos
            $_POST["tarjeta"] = str_replace(" ", "", $_POST["tarjeta"]);
            if(!preg_match("/^[0-9]{16}$/", $_POST["tarjeta"])){
                $errores["tarjeta"] = "<p class='error'>La tarjeta debe contener 16 dígitos</p>";
                $datos["tarjeta"] = "";
            }else{
                // Comprobar que la tarjeta es válida
                if(!validarTarjeta($_POST["tarjeta"])){
                    $errores["tarjeta"] = "<p class='error'>La tarjeta no es válida</p>";
                    $datos["tarjeta"] = "";
                }else{
                    $datos["tarjeta"] = str_replace(" ","", $_POST["tarjeta"]);
                }
            }
        }

        // Comprobamos si se ha mandado rol
        if(!empty($_POST["rol"])){
            if($_POST["rol"] == "Recepcionista" || $_POST["rol"] == "Administrador" || $_POST["rol"] == "Cliente"){
                $datos["rol"] = $_POST["rol"];
            } else {
                $_POST["rol"] = "Cliente";
            }
        }

        if(!empty($_POST["enviar-registro"])){
            if(empty($errores)){
                $datos["correcto"] = true;
            }
        }
    }

    return [$errores, $datos];
}

function validarLogIn($conexion) {
    // Inicializamos los arrays de errores y datos
    $errores = array();
    $datos = array();
    if(isset($_POST["iniciar-sesion"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobar email
        if(empty($_POST["email-sesion"])){
            $errores["email-sesion"] = "<p class='error'>El email no puede estar vacío</p>";
            $datos["email-sesion"] = "";
        }else{
            // Comprobar que el email es válido
            if(!filter_var($_POST["email-sesion"], FILTER_VALIDATE_EMAIL)){
                $errores["email-sesion"] = "<p class='error'>El email no es válido</p>";
                $datos["email-sesion"] = "";
            }else{
                if(checkEmail($conexion, $_POST["email-sesion"])){
                    $datos["email-sesion"] = $_POST["email-sesion"];
                } else {
                    $errores["email-sesion"] = "<p class='error'>El email no está registrado</p>";
                    $datos["email-sesion"] = "";
                }
            }
        }

        // Comprobar clave
        if(empty($_POST["clave-sesion"])){
            $errores["clave-sesion"] = "<p class='error'>La clave no puede estar vacía</p>";
            $datos["clave-sesion"] = "";
        } else {
            $datos["clave-sesion"] = checkInyection($_POST["clave-sesion"]);
        }

        return [$errores, $datos];

    }
}

// Función para validar el formulario de cambio de datos del usuario
function validarCambioDatos($conexion){
    $errores = array();
    $datos = array();

    if(isset($_POST["cambiar-datos-usuario"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        // Comprobamos si el email no está vacio
        if(!empty($_POST["email"])){
            // Comprobar que el email es válido
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $errores["email"] = "<p class='error'>El email no es válido</p>";
                $datos["email"] = "";
            }else{
                if(!checkEmail($conexion, $_POST["email"])){
                    $datos["email"] = $_POST["email"];
                } else {
                    $errores["email"] = "<p class='error'>Ya existe una cuenta con este email</p>";
                    $datos["email"] = "";
                }
            }
        }

        // Comprobamos si la tarjeta no está vacía
        if(!empty($_POST["tarjeta"])){
            // Comprobar que la tarjeta tiene 16 dígitos
            $_POST["tarjeta"] = str_replace(" ", "", $_POST["tarjeta"]);
            if(!preg_match("/^[0-9]{16}$/", $_POST["tarjeta"])){
                $errores["tarjeta"] = "<p class='error'>La tarjeta debe contener 16 dígitos</p>";
                $datos["tarjeta"] = "";
            }else{
                // Comprobar que la tarjeta es válida
                if(!validarTarjeta($_POST["tarjeta"])){
                    $errores["tarjeta"] = "<p class='error'>La tarjeta no es válida</p>";
                    $datos["tarjeta"] = "";
                }else{
                    $datos["tarjeta"] = str_replace(" ","", $_POST["tarjeta"]);
                }
            }
        }

        // Comprobamos si la clave no está vacía
        if(!empty($_POST["clave"])){
            $_POST["clave"] = checkInyection($_POST["clave"]);
            // Comprobar que la clave tiene al menos 5 caracteres
            if(strlen($_POST["clave"]) < 5){
                $errores["clave"] = "<p class='error'>La clave debe tener al menos 5 caracteres</p>";
                $datos["clave"] = "";
                $datos["clave-repetida"] = "";
            }else{
                $datos["clave"] = $_POST["clave"];
                // Comprobar que la clave repetida coincide
                if($_POST["clave"] != $_POST["clave-repetida"]){
                    $errores["clave-repetida"] = "<p class='error'>Las claves no coinciden</p>";
                    $datos["clave-repetida"] = "";
                } else {
                    $datos["clave-repetida"] = $_POST["clave-repetida"];
                }
            }
        }

    }

    return [$errores, $datos];
}

// Función para validar el formulario de habitaciones

function validarDatosHabitaciones($conexion) {
    // Inicializamos los arrays de errores y datos
    $errores = array();
    $datos = array();

    if((isset($_POST["enviar-habitacion"]) || isset($_POST["enviar-modificar-habitacion"])) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobamos que el número de habitación no es nulo
        if(empty($_POST["habitacion"])) {
            $errores["habitacion"] = "<p class='error'>El Nº Habitación no puede estar vacio</p>";
            $datos["habitacion"] = "";
        } else {
            $_POST["habitacion"] = checkInyection($_POST["habitacion"]);
            // Comprobamos si existe una entrada con el mismo nombre en la BD
            if(checkNumeroHabitacion($conexion, $_POST["habitacion"]) && isset($_POST["enviar-habitacion"])) {
                $datos["habitacion"] = "";
                $errores["habitacion"] = "<p class='error'>Ya existe una habitación con ese nombre</p>";
            } else {
                $datos["habitacion"] = $_POST["habitacion"];
            }
        }

        // Comprobamos que la capacidad no sea nula
        if(empty($_POST["capacidad"])){
            $errores["capacidad"] = "<p class='error'>La capacidad no puede estar vacia</p>";
            $datos["capacidad"] = "";
        } else {
            // Comprobar que es un número entero
            if(!is_numeric($_POST["capacidad"]) || !is_int($_POST["capacidad"] + 0)){
                $errores["capacidad"] = "<p class='error'>La capacidad debe ser un número entero</p>";
                $datos["capacidad"] = "";
            }else{
                $datos["capacidad"] = $_POST["capacidad"];
            }
        }

        // Comprobamos que el precio no sea nulo
        if(empty(($_POST["precio"]))){
            $errores["precio"] = "<p class='error'>El precio no puede estar vacío</p>";
            $datos["precio"] = "";
        } else {
            // Comprobar que es un double
            if(!is_numeric($_POST["precio"]) || !is_double($_POST["precio"] + 0)){
                $errores["precio"] = "<p class='error'>El precio debe ser un número decimal</p>";
                $datos["precio"] = "";
            }else{
                $datos["precio"] = $_POST["precio"];
            }
        }

        // Comprobamos que la descripción no sea vacía
        if(empty($_POST["descripcion"])) {
            $errores["descripcion"] = "<p class='error'>La descripción no puede estar vacía</p>";
            $datos["descripcion"] = "";
        } else { 
            $datos["descripcion"] = checkInyection($_POST["descripcion"]);
        }

        // Validar Fotografías
        if(!empty($_FILES['fotos']['name'][0])) {
            $allowed_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
            foreach($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                $file_type = exif_imagetype($tmp_name);
                if(!in_array($file_type, $allowed_types)) {
                    break;
                } else {
                    // Leer la imagen en formato base64
                    $imagen_base64 = base64_encode(file_get_contents($tmp_name));
                    // Añadir la imagen al array de datos
                    $datos["fotos"][] = $imagen_base64;
                }
            }
        }

        // Rescatar fotos guardadas
        if (isset($_POST['fotos_guardadas'])) {
            if (!isset($datos["fotos"])) {
                $datos["fotos"] = array();
            }
            $datos["fotos"] = array_merge($datos["fotos"], $_POST['fotos_guardadas']);
        }

        // Comprobamos si no hay errores
        if(!empty($_POST["enviar-habitacion"]) || !empty($_POST["enviar-modificar-habitacion"])){
            if(empty($errores)){
                $datos["correcto"] = true;
            }
        }
    }

    return [$errores, $datos];

}

// Función para validar el formulario de reservas
function validarDatosReserva() {
    // Inicializamos los arrays de errores y datos
    $errores = array();
    $datos = array();

    if(isset($_POST["enviar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobamos que el número de habitación no es nulo
        if(empty($_POST["numeropersonas"])){
            $errores["numeropersonas"] = "<p class='error'>El número de personas no puede estar vacío</p>";
            $datos["numeropersonas"] = "";
        } else {
            // Comprobar que es un número entero
            if(!is_numeric($_POST["numeropersonas"]) || !is_int($_POST["numeropersonas"] + 0)){
                $errores["numeropersonas"] = "<p class='error'>El número de personas debe ser un número entero</p>";
                $datos["numeropersonas"] = "";
            }else{
                $datos["numeropersonas"] = $_POST["numeropersonas"];
            }
        }

        //Comprobamos que la fecha de entrada no sea nula
        if(empty($_POST["entrada"])){
            $errores["entrada"] = "<p class='error'>La fecha de entrada no puede estar vacía</p>";
            $datos["entrada"] = "";
        } else {
            $datos["entrada"] = $_POST["entrada"];
        }

        // Comprobamos que la fecha de salida no sea nula y sea mayor que la de entrada
        if(empty($_POST["salida"])){
            $errores["salida"] = "<p class='error'>La fecha de salida no puede estar vacía</p>";
            $datos["salida"] = "";
        } else {
            // Comprobar que la fecha de salida es mayor que la de entrada
            if($_POST["salida"] <= $_POST["entrada"]){
                $errores["salida"] = "<p class='error'>La fecha de salida debe ser posterior a la de entrada</p>";
                $datos["salida"] = "";
            }else{
                $datos["salida"] = $_POST["salida"];
            }
        }

        // Hacer sticky los comentarios
        if(empty($_POST["comentario"])){
            $datos["comentario"] = "";
        } else {
            $datos["comentario"] = checkInyection($_POST["comentario"]);
        }

        // Comprobamos si no hay errores
        if(!empty($_POST["enviar-reserva"])){
            if(empty($errores)){
                $datos["correcto"] = true;
            }
        }
    }

    return [$errores, $datos];
        
}

// Función para validar datos de reforma
function validarDatosReforma() {
    // Inicializamos los arrays de errores y datos
    $errores = array();
    $datos = array();

    if(isset($_POST["enviar-reserva"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobamos que la habitación no sea nula
        if(empty($_POST["habitacion-reforma"])){
            $errores["habitacion-reforma"] = "<p class='error'>La habitación no puede estar vacía</p>";
            $datos["habitacion-reforma"] = "";
        } else {
            $datos["habitacion-reforma"] = $_POST["habitacion-reforma"];
        }
        //Comprobamos que la fecha de entrada no sea nula
        if(empty($_POST["entrada"])){
            $errores["entrada"] = "<p class='error'>La fecha de entrada no puede estar vacía</p>";
            $datos["entrada"] = "";
        } else {
            $datos["entrada"] = $_POST["entrada"];
        }

        // Comprobamos que la fecha de salida no sea nula y sea mayor que la de entrada
        if(empty($_POST["salida"])){
            $errores["salida"] = "<p class='error'>La fecha de salida no puede estar vacía</p>";
            $datos["salida"] = "";
        } else {
            // Comprobar que la fecha de salida es mayor que la de entrada
            if($_POST["salida"] <= $_POST["entrada"]){
                $errores["salida"] = "<p class='error'>La fecha de salida debe ser posterior a la de entrada</p>";
                $datos["salida"] = "";
            }else{
                $datos["salida"] = $_POST["salida"];
            }
        }
        // Comprobamos si no hay errores
        if(!empty($_POST["enviar-reserva"])){
            if(empty($errores)){
                $datos["correcto"] = true;
            }
        }
    }

    return [$errores, $datos];
}

function validarFormularioFiltro($conexion){
    // Inicializamos los arrays de datos
    $datos = array();
    $datos_cookie = explode(",", $_COOKIE["filtros-reserva"]);

    if(isset($_POST["filtros-reservas"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        // Comprobamos que la paginación no sea nula
        if(empty($_POST["paginacion"])){
            $datos["paginacion"] = $datos_cookie[0];
        } else {
            // Comprobar que es un número entero
            if(!is_numeric($_POST["paginacion"]) || !is_int($_POST["paginacion"] + 0)){
                $datos["paginacion"] = $datos_cookie[0];
            }else{
                $datos["paginacion"] = $_POST["paginacion"];
            }
        }
        // Comprobamos que el orden no sea nulo
        if(empty($_POST["ordenamiento"])){
            $datos["ordenamiento"] = $datos_cookie[1];
        } else {
            // Comprobar que es una de las opciones permitidas
            if($_POST["ordenamiento"] != "antiguedad_asc" && $_POST["ordenamiento"] != "antiguedad_desc" && $_POST["ordenamiento"] != "duracion_asc" && $_POST["ordenamiento"] != "duracion_desc"){
                $datos["ordenamiento"] = $datos_cookie[1];
            }else{
                $datos["ordenamiento"] = $_POST["ordenamiento"];
            }
        }
        // Comprobamos que se haya establecido un rango de fechas
        if(empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){
            $datos["fecha_inicio"] = "";
            $datos["fecha_fin"] = "";
        } else {
            if(empty($_POST["fecha_inicio"])){
                // Como necesitamos un rango, le paso el mínimo de la tabla
                $datos["fecha_inicio"] = obtenerMinFechaEntrada($conexion)[1]["minFecha"];
            } else {
                $datos["fecha_inicio"] = $_POST["fecha_inicio"];
            }
            if(empty($_POST["fecha_fin"])){
                // Como necesitamos un rango, le paso el máximo de la tabla
                $datos["fecha_fin"] = obtenerMaxFechaSalida($conexion)[1]["maxFecha"];
            } else {
                $datos["fecha_fin"] = $_POST["fecha_fin"];
            }
        }
        // Comprobar que el comentario no sea nulo
        if(empty($_POST["comentario"])){
            $datos["comentario"] = "";
        } else {
            $datos["comentario"] = checkInyection($_POST["comentario"]);
        }
    }

    return $datos;
}

function validarFiltroUsuarios(){
    // Inicializamos los arrays de datos
    $datos = array();
    $datos_cookie = explode(",", $_COOKIE["filtros-usuarios"]);

    if(isset($_POST["filtros-usuarios"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        // Comprobamos que la paginación no sea nula
        if(empty($_POST["paginacion"])){
            $datos["paginacion"] = $datos_cookie[0];
        } else {
            // Comprobar que es un número entero
            if(!is_numeric($_POST["paginacion"]) || !is_int($_POST["paginacion"] + 0)){
                $datos["paginacion"] = $datos_cookie[0];
            }else{
                $datos["paginacion"] = $_POST["paginacion"];
            }
        }

        // Comprobamos que el dni no sea nulo
        if(empty($_POST["dni"])){
            $datos["dni"] = "";
        } else {
            // Comprobar que es un dni válido
            if(!preg_match("/^[0-9]{8}[A-Z]$/", $_POST["dni"])){
                $datos["dni"] = $datos_cookie[1];
            }else{
                if(!validarLetraDNI($_POST["dni"])){
                    $datos["dni"] = $datos_cookie[1];
                }else{
                    $datos["dni"] = $_POST["dni"];
                }
            }
        }

        // Comprobamos que el email no sea nulo
        if(empty($_POST["email"])){
            $datos["email"] = "";
        } else {
            // Comprobar que es un email válido
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $datos["email"] = $datos_cookie[2];
            }else{
                $datos["email"] = $_POST["email"];
            }
        }

        // Comprobamos que el rol no sea nulo
        if(empty($_POST["rol"])){
            $datos["rol"] = $datos_cookie[3];
        } else {
            // Comprobar que es un rol válido
            if($_POST["rol"] != "Administrador" && $_POST["rol"] != "Recepcionista" && $_POST["rol"] != "Cliente" && $_POST["rol"] != "Todos"){
                $datos["rol"] = $datos_cookie[3];
            }else{
                if($_POST["rol"] == "Todos"){
                    $datos["rol"] = "";
                }else{  
                    $datos["rol"] = $_POST["rol"];
                }
            }
        }
    }

    return $datos;
}

function validarFiltroLogs(){
    // Inicializamos los arrays de datos
    $datos = array();
    $datos_cookie = explode(",", $_COOKIE["filtros-logs"]);

    if(isset($_POST["filtros-logs"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
        // Comprobamos que la paginación no sea nula
        if(empty($_POST["paginacion"])){
            $datos["paginacion"] = $datos_cookie[0];
        } else {
            // Comprobar que es un número entero
            if(!is_numeric($_POST["paginacion"]) || !is_int($_POST["paginacion"] + 0)){
                $datos["paginacion"] = $datos_cookie[0];
            }else{
                $datos["paginacion"] = $_POST["paginacion"];
            }
        }

        // Comprobamos si el tipo no es nulo
        if(empty($_POST["tipo"])){
            $datos["tipo"] = $datos_cookie[1];
        } else {
            if($_POST["tipo"] == "Todos"){
                $datos["tipo"] = "";
            }else{  
                $datos["tipo"] = $_POST["tipo"];
            }
        }
    }

    return $datos;
}

function validarArchivoSQL(){
    // Inicializamos los arrays de errores
    $errores = array();
    $file = null;

    if (isset($_POST["restaurar-backup"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // Comprobamos que se ha subido un archivo sql
        if (empty($_FILES["backup"]["tmp_name"])) {
            $errores["backup"] = "<p class='error'>No se ha seleccionado ningún archivo</p>";
        } else {
            // Comprobamos que el archivo es un sql
            if ($_FILES["backup"]["type"] != "application/sql") {
                $errores["backup"] = "<p class='error'>El archivo debe ser un archivo SQL</p>";
            } else {
                $file = $_FILES["backup"]["tmp_name"];
            }
        }
    }

    return [$errores, $file];
}

?>