<?php 
// Función para iniciar el documento HTML
function HTML_init() {
    echo <<< HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Hotel Alhambra</title>
            <link rel="stylesheet" href="./css/index.css">
        </head>
        <body>
    HTML;
}

// Función para cerrar el documento HTML
function HTML_close() {
    echo <<< HTML
        </body>
        </html>
    HTML;
}

// Función para generar el header
function HTML_header() {
    echo <<< HTML
        <header>
            <div class="logo">
                <div class="logo-image">
                    <img src="img/logo-hotel.png" alt="Logo del hotel">
                </div>
                <h1>Hotel Alhambra</h1>
            </div>
            <aside class="contact-info">
                <img src="img/contacto.png" alt="Datos de Contacto">
            </aside>
        </header>
    HTML;
}

// Función para generar el menú de navegación
function HTML_nav() {
    echo <<< HTML
        <nav>
            <ul>
                <li><a href="ej14-inicio.html">Inicio</a></li>
                <li><a href="ej14-habitaciones.html">Habitaciones</a></li>
                <li><a href="ej14-servicios.html">Servicios</a></li>
                <li><a href="ej14-reservas.html">Reservas</a></li>
                <li><a href="ej14-datos.html">Datos</a></li>
            </ul>
            <ul class="sesion-pantalla-reducida">
                <li><a href="">LogIn</a></li>
            </ul>
        </nav>
    HTML;
}

// Función para iniciar el contenedor principal
function HTML_main_container() {
    echo <<< HTML
        <div class="container">
        <div class="main">
    HTML;
}

// Función para generar el aside
function HTML_aside() {
    echo <<< HTML
        </div>
        <aside class="zona-lateral">
            <div class="inicio-sesion">
                <h2>Inicio de sesión y perfil de usuario</h2>
            </div>
            <section class="informacion-2nivel">
                <h2>Información de Interés (Información de Segundo Nivel)</h2>
                <ul>
                    <li><a href="">Info 1</a></li>
                    <li><a href="">Info 2</a></li>
                </ul>
            </section>
        </aside>
    </div>
    HTML;
}

// Función para generar el footer
function HTML_footer() {
    echo <<< HTML
        <footer>
            <div class="fila">
                <div>
                    <p>Información de autores</p>
                </div>
                <div>
                    <p><a href="css-adaptable.pdf">Documentación CSS</a></p>
                </div>
                <div>
                    <p>Copyright</p>
                </div>
            </div>
            <div class="contacto-reducido">
                <div class="fila">
                    <img src="img/contacto.png" alt="Datos de Contacto">
                </div>
            </div>
        </footer>
    HTML;
}
?>

        