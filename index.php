<?php 
require_once("html.php");
require_once("funcionesValidacion.php");

session_start();

if (isset($_POST["enviar-registro"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    [$_SESSION["errores-registro"], $_SESSION["datos-registro"]] = validarDatosRegistro();
}

HTML_init();
HTML_header();
HTML_nav();
HTML_main_container() ;

// Gestion del contenido a generar en funcion de la pagina solicitada
if(isset($_GET["pagina"])) {
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