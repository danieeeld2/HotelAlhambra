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

// Comprobamos si se ha enviado el formulario de registro
if (isset($_POST["enviar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-registro"], $_SESSION["datos-registro"]] = validarDatosRegistro();
}
if (isset($_POST["confirmar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = insertarUsuario($conexion, $_SESSION["datos-registro"]);
    if($resultado) {
        unset($_SESSION["datos-registro"]);
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
            HTML_pagina_habitaciones();
            break;
        case "servicios":
            HTML_pagina_servicios();
            break;
        case "registro":
            HTML_form_registro() ;
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
?>