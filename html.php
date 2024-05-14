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
                <li><a href="index.php?pagina=inicio">Inicio</a></li>
                <li><a href="index.php?pagina=habitaciones">Habitaciones</a></li>
                <li><a href="index.php?pagina=servicios">Servicios</a></li>
                <li><a href="index.php?pagina=registro">Registro</a></li>
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
function HTML_aside() { ?>
    </div>
    <aside class="zona-lateral">
        <div class="inicio-sesion">
            <form action="" method="post" novalidate>
                <p>
                    <label for="idemail">Email:</label>
                    <input type="email" id="idemail" name="email-sesion" value=<?php if(isset($_SESSION["datos-login"]["email-sesion"])) echo  $_SESSION["datos-login"]["email-sesion"] ?>>
                </p>
                <?php if(isset($_SESSION["errores-login"]["email-sesion"])) echo $_SESSION["errores-login"]["email-sesion"] ?>
                <p>
                    <label for="idpassword">Contraseña:</label>
                    <input type="password" id="idpassword" name="clave-sesion">
                </p>
                <?php if(isset($_SESSION["errores-login"]["clave-sesion"])) echo $_SESSION["errores-login"]["clave-sesion"] ?>
                <?php if(isset($_SESSION["error-login"])) echo $_SESSION["error-login"] ?>
                <div class="boton-lateral">
                    <input type="submit" value="Iniciar Sesión" name="iniciar-sesion" id="boton-enviar">
                </div>
            </form>
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
<?php }

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

function HTML_pagina_inicio() {
    echo <<< HTML
        <div>
            <main>
                <h2>Sobre Nosotros</h2>
                <p>Hotel Alhambra ofrece una experiencia única en el corazón de Granada, situado en el pintoresco barrio del Albaicín con impresionantes vistas a la 
                    majestuosa Alhambra. Nuestro hotel combina la elegancia del diseño contemporáneo con el encanto tradicional andaluz. 
                    Disfruta de habitaciones confortables y acogedoras, terrazas con vistas panorámicas incomparables y una atención 
                    personalizada que te hará sentir como en casa. Sumérgete en la historia y la cultura de Granada mientras te relajas en nuestro 
                    oasis de tranquilidad en la cima de la colina.</p>
            </main>
            <section>
                <h2>Nuestro Espacio</h2>
                <div class="galeria">
                    <div class="imagen">
                        <img src="img/vistas1.jpg" alt="Vistas 1">
                    </div>
                    <div class="imagen">
                        <img src="img/terraza1.jpg" alt="Terraza 1">
                    </div>
                    <div class="imagen">
                        <img src="img/hab1.jpg" alt="Habitacion 1">
                    </div>
                    <div class="imagen">
                        <img src="img/hab2.jpg" alt="Habitacion 2">
                    </div>
                </div>
            </section>
        </div>
        <section>
            <h2>Nuestras Actividades</h2>
            <ul>
                <li><a href="">Enlace a Evento 1</a></li>
                <li><a href="">Recurso de Interés 1</a></li>
            </ul>
        </section>
    HTML;
}

function HTML_pagina_habitaciones() {
    echo <<< HTML
        <main>
            <section class="tarjeta">
                <h2>Suite Alhambra</h2>
                <section>
                    <p>Nuestra Suite Alhambra es el epítome del lujo y la comodidad en Hotel Alhambra Hotel. 
                        Diseñada para ofrecer una experiencia inolvidable, esta espaciosa suite combina elegancia contemporánea 
                        con detalles inspirados en la rica historia de Granada. Disfruta de vistas panorámicas a la majestuosa 
                        Alhambra desde tu propia terraza privada, donde podrás relajarte y maravillarte con la belleza de este emblemático monumento. 
                        La Suite Alhambra cuenta con una amplia sala de estar, dormitorio independiente, baño lujoso y todas las comodidades 
                        modernas para garantizar una estancia inigualable. Sumérgete en el lujo y la serenidad mientras 
                        disfrutas de la experiencia única que solo nuestro hotel puede ofrecer.</p>
                    <ul>
                        <li>Capacidad: 2 adultos</li>
                        <li>Camas: 1 cama king size</li>
                        <li>Tamaño: 60 m²</li>
                        <li>Vistas: Alhambra</li>
                        <li>Desayuno incluido</li>
                        <li>Wifi gratis</li>
                        <li>TV de pantalla plana</li>
                        <li>Minibar</li>
                        <li>Caja fuerte</li>
                        <li>Secador de pelo</li>
                        <li>Albornoz y zapatillas</li>
                        <li>Artículos de baño de lujo</li>
                    </ul>
                </section>
                <section>
                    <h2>Observa la Suite Alhambra</h2>
                    <div class="galeria">
                        <div class="imagen">
                            <img src="img/hab-suite.jpg" alt="Habitacion Suite">
                        </div>
                        <div class="imagen">
                            <img src="img/baño-suite.jpg" alt="Baño Suite">
                        </div>
                        <div class="imagen">
                            <img src="img/terraza-suite.jpg" alt="Terraza Suite">
                        </div>
                    </div>
                </section>
            </section>
            <section class="tarjeta">
                <h2>Individual</h2>
                <section>
                    <p>Experimenta la serenidad y el confort en nuestra Habitación Individual en el Hotel Alhambra. 
                        Diseñada para el viajero que valora la privacidad y la comodidad, esta acogedora habitación ofrece un 
                        refugio tranquilo en el corazón de Granada. Decorada con un estilo elegante y funcional, la 
                        Habitación Individual está equipada con todas las comodidades modernas para garantizar una estancia confortable. 
                        Desde su ventana, podrás disfrutar de vistas a los encantadores callejones del Albaicín o al tranquilo patio andaluz del hotel. 
                        Sumérgete en la auténtica atmósfera de Granada mientras te relajas en tu propio espacio privado en el Hotel Alhambra.</p>
                    <ul>
                        <li>Capacidad: 1 adulto</li>
                        <li>Camas: 1 cama individual</li>
                        <li>Tamaño: 20 m²</li>
                        <li>Vistas: Patio interior</li>
                        <li>Desayuno incluido</li>
                        <li>Wifi gratis</li>
                        <li>TV de pantalla plana</li>
                        <li>Minibar</li>
                        <li>Caja fuerte</li>
                        <li>Secador de pelo</li>
                        <li>Artículos de baño de lujo</li>
                    </ul>
                </section>
                <section>
                    <h2>Observa la Habitación Individual</h2>
                    <div class="galeria">
                        <div class="imagen">
                            <img src="img/hab-individual.png" alt="Habitacion Individual">
                        </div>
                        <div class="imagen" id="bañera">
                            <img src="img/baño-individual.png" alt="Baño Individual">
                        </div>
                    </div>
                </section>
            </section>
        </main>
    HTML;
}

function HTML_pagina_servicios() {
    echo <<< HTML
        <main>
            <section class="tarjeta">
                <h2>Piscina</h2>
                <div>
                    <p>
                        Sumérgete en un oasis de relajación y frescura en nuestra exquisita piscina del Hotel Alhambra. 
                        Rodeada de exuberante vegetación y con vistas panorámicas a la icónica Alhambra, nuestra piscina 
                        ofrece el escenario perfecto para escapar del bullicio de la ciudad y disfrutar de momentos de paz y tranquilidad. 
                        Da un refrescante chapuzón en las aguas cristalinas mientras disfrutas del cálido sol andaluz o relájate 
                        en una de nuestras cómodas tumbonas con una refrescante bebida en la mano. Ya sea para un revitalizante baño matutino 
                        o para disfrutar de una noche bajo las estrellas, nuestra piscina te invita a sumergirte en una experiencia de 
                        verdadero lujo y bienestar en pleno corazón de Granada
                    </p>
                    <ul>
                        <li>Horario de 9:00-19:00</li>
                        <li>Servicio de Socorrismo</li>
                        <li>Prohibido hacer ruido</li>
                        <li>Los niños deben estar con vigilancia de los padres</li>
                    </ul>
                </div>
                <section>
                    <h2>Nuestra Piscina</h2>
                    <div class="galeria">
                        <div class="imagen">
                            <img src="img/piscina.jpg" alt="Piscina">
                        </div>
                    </div>
                </section>
            </section>
            <section class="tarjeta">
                <h2>Buffet</h2>
                <div>
                    <p>
                        Deléitate con una experiencia culinaria excepcional en nuestro buffet del Hotel Alhambra. 
                        Con una amplia variedad de platos tanto locales como internacionales, nuestro buffet 
                        ofrece una fusión de sabores exquisitos que satisfarán incluso a los paladares más exigentes. Desde 
                        auténticas especialidades andaluzas hasta delicias internacionales cuidadosamente preparadas, 
                        cada plato está elaborado con ingredientes frescos y de la más alta calidad. 
                        Disfruta de una selección de ensaladas frescas, entrantes tentadores, 
                        platos principales gourmet y exquisitos postres, todo presentado en un ambiente acogedor y elegante. 
                        Ya sea para un desayuno energético, un almuerzo ligero o una cena indulgente, nuestro 
                        buffet es el lugar perfecto para disfrutar de una experiencia gastronómica inolvidable en el corazón de Granada.
                    </p>
                    <ul>
                        <li>Desayuno</li>
                        <li>Comida</li>
                        <li>Cena</li>
                        <li>Snacks</li>
                    </ul>
                </div>
                <section>
                    <h2>Nuestro Buffet</h2>
                    <div class="galeria">
                        <div class="imagen">
                            <img src="img/buffet.jpg" alt="Buffet">
                        </div>
                </section>
            </section>
        </main>
    HTML;
}

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

<?php
function HTML_error_path(){
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>La página solicitada no existe</h2>
            </div>
        </main>
    HTML;
}

?>
