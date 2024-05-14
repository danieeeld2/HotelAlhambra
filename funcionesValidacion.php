<?php 
require_once("funcionesAuxiliares.php");
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
            if(!preg_match("/^[0-9]{16}$/", $_POST["tarjeta"])){
                $errores["tarjeta"] = "<p class='error'>La tarjeta debe contener 16 dígitos</p>";
                $datos["tarjeta"] = "";
            }else{
                // Comprobar que la tarjeta es válida
                if(!validarTarjeta($_POST["tarjeta"])){
                    $errores["tarjeta"] = "<p class='error'>La tarjeta no es válida</p>";
                    $datos["tarjeta"] = "";
                }else{
                    $datos["tarjeta"] = $_POST["tarjeta"];
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
?>