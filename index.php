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
HTML_pagina_inicio();
HTML_aside();
HTML_footer() ;
HTML_close();
?>