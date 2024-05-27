<?php 
require_once("funcionesAuxiliares.php");
require_once("funcionesBD.php");
// Función para validar los datos del formulario de registro
function validarDatosRegistro(){
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
                $datos["email"] = $_POST["email"];
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
?>