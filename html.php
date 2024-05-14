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

// *****************************************************

function HTML_form_registro() { ?>
    <main>
        <section class="registro-usuarios">
            <h2>Registro de Usuarios</h2>
            <?php if(isset($_SESSION["datos-registro"]["correcto"]))  echo "<h2 class='datos-recibidos'>Los datos se han recibido correctamente</h2>" ?>
            <?php if(isset($_SESSION["datos-registro"]["correcto"])) $disable = "disabled"; else $disable = ""; ?>
            <form action="" method="post" novalidate>
                <fieldset>
                    <legend>Datos del Usuario</legend>
                    <p>
                        <label for="idnombre">Nombre:</label>
                        <input type="text" id="idnombre" name="nombre" placeholder="(Obligatorio)" value=<?php if(isset($_SESSION["datos-registro"]["nombre"])) echo $_SESSION["datos-registro"]["nombre"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["nombre"])) echo $_SESSION["errores-registro"]["nombre"] ?>
                    <p>
                        <label for="idapellidos">Apellidos:</label>
                        <input type="text" id="idapellidos" name="apellidos" placeholder="(Obligatorio)" value=<?php if(isset($_SESSION["datos-registro"]["apellidos"])) echo $_SESSION["datos-registro"]["apellidos"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["apellidos"])) echo $_SESSION["errores-registro"]["apellidos"] ?>
                    <p>
                        <label for="iddni">DNI:</label>
                        <input type="text" id="iddni" name="dni" placeholder="(Solo DNIs Españoles)" value=<?php if(isset($_SESSION["datos-registro"]["dni"])) echo $_SESSION["datos-registro"]["dni"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["dni"])) echo $_SESSION["errores-registro"]["dni"] ?>
                </fieldset>
                <fieldset>
                    <legend>Datos de la Cuenta</legend>
                    <p>
                        <label for="idemail">Email:</label>
                        <input type="email" id="idemail" name="email" placeholder="(Obligatorio)" value=<?php if(isset($_SESSION["datos-registro"]["email"])) echo $_SESSION["datos-registro"]["email"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["email"])) echo $_SESSION["errores-registro"]["email"] ?>
                    <p>
                        <label for="idpassword">Contraseña:</label>
                        <input type="password" id="idpassword" name="clave" placeholder="(Mínimo 5 caracteres)" value=<?php if(isset($_SESSION["datos-registro"]["clave"])) echo $_SESSION["datos-registro"]["clave"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["clave"])) echo $_SESSION["errores-registro"]["clave"] ?>
                    <p>
                        <label for="idpassword2">Repetir Contraseña:</label>
                        <input type="password" id="idpassword2" name="clave-repetida" placeholder="(Repetir contraseña)" value=<?php if(isset($_SESSION["datos-registro"]["clave-repetida"])) echo $_SESSION["datos-registro"]["clave-repetida"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["clave-repetida"])) echo $_SESSION["errores-registro"]["clave-repetida"] ?>
                </fieldset>
                <fieldset>
                    <legend>Datos de Pago</legend>
                    <p>
                        <label for="idtarjeta">Tarjeta de Crédito:</label>
                        <input type="text" id="idtarjeta" name="tarjeta" placeholder="(16 dígitos)" value=<?php if(isset($_SESSION["datos-registro"]["tarjeta"])) echo $_SESSION["datos-registro"]["tarjeta"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if(isset($_SESSION["errores-registro"]["tarjeta"])) echo $_SESSION["errores-registro"]["tarjeta"] ?>
                </fieldset>
                <div class="boton">
                    <?php 
                    if(isset($_SESSION["datos-registro"]["correcto"])){
                        echo "<input type='submit' value='Confirmar Datos' name='confirmar-registro' id='boton-enviar'>";
                    } else {
                        echo "<input type='submit' value='Enviar Datos' name='enviar-registro' id='boton-enviar'>";
                    }
                    ?>
                </div>
            </form>
        </section>
    </main>
<?php }
?>

        