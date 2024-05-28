<?php
// Función para iniciar el documento HTML
function HTML_init()
{
    echo <<< HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Hotel Alhambra</title>
            <link rel="stylesheet" href="./css/index.css">
        HTML;
    if (isset($_GET["pagina"]) && $_GET["pagina"] == "gestion-habitaciones") {
        echo "<script src='previsualizacion.js'></script>";
    }
    echo <<< HTML
        </head>
        <body>
    HTML;
}

// Función para cerrar el documento HTML
function HTML_close()
{
    echo <<< HTML
        </body>
        </html>
    HTML;
}

// Función para generar el header
function HTML_header()
{
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
function HTML_nav()
{ ?>
    <nav>
        <ul>
            <li><a href="index.php?pagina=inicio">Inicio</a></li>
            <li><a href="index.php?pagina=habitaciones">Habitaciones</a></li>
            <li><a href="index.php?pagina=servicios">Servicios</a></li>
            <?php if (!$_SESSION["iniciado-sesion"]) echo "<li><a href='index.php?pagina=registro'>Registro</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Recepcionista") echo "<li><a href='index.php?pagina=gestion-habitaciones'>Gestión Habitaciones</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Cliente") echo "<li><a href='index.php?pagina=reservas'>Reservas</a></li>" ?>
        </ul>
        <ul class="sesion-pantalla-reducida">
            <li><a href=""><?php if (isset($_SESSION["iniciado-sesion"]) && !$_SESSION["iniciado-sesion"]) echo "LogIn";
                            else echo "LogOut" ?> </a></li>
        </ul>
    </nav>
<?php }

// Función para iniciar el contenedor principal
function HTML_main_container()
{
    echo <<< HTML
        <div class="container">
        <div class="main">
    HTML;
}

function HTML_formulario_login()
{ ?>
    <form action="" method="post" novalidate>
        <p>
            <label for="idemail">Email:</label>
            <input type="email" id="idemail" name="email-sesion" value=<?php if (isset($_SESSION["datos-login"]["email-sesion"])) echo  $_SESSION["datos-login"]["email-sesion"] ?>>
        </p>
        <?php if (isset($_SESSION["errores-login"]["email-sesion"])) echo $_SESSION["errores-login"]["email-sesion"] ?>
        <p>
            <label for="idpassword">Contraseña:</label>
            <input type="password" id="idpassword" name="clave-sesion">
        </p>
        <?php if (isset($_SESSION["errores-login"]["clave-sesion"])) echo $_SESSION["errores-login"]["clave-sesion"] ?>
        <?php if (isset($_SESSION["error-login"])) echo $_SESSION["error-login"] ?>
        <div class="boton-lateral">
            <input type="submit" value="Iniciar Sesión" name="iniciar-sesion" id="boton-enviar">
        </div>
    </form>
<?php }

function HTML_logout()
{ ?>
    <p id="usuario-iniciado">Bienvenido, <?php echo $_SESSION["usuario"] ?>. Tu rol es <?php echo $_SESSION["rol"] ?></p>
    <form action="" method="post" novalidate>
        <div class="boton-lateral">
            <div class="boton-logout">
                <input type="submit" value="Cerrar Sesión" name="cerrar-sesion" id="boton-logout">
            </div>
        </div>
    </form>
<?php }

// Función para generar el aside
function HTML_aside()
{ ?>
    </div>
    <aside class="zona-lateral">
        <div class="inicio-sesion">
            <?php if (isset($_SESSION["iniciado-sesion"]) && !$_SESSION["iniciado-sesion"]) HTML_formulario_login();
            else if (isset($_SESSION["iniciado-sesion"]) && $_SESSION["iniciado-sesion"]) HTML_logout() ?>
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
function HTML_footer()
{
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

function HTML_pagina_inicio()
{
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

function HTML_pagina_habitaciones($conexion)
{
    $habitaciones = getHabitaciones($conexion);
    if ($habitaciones[0]) {
        $habitaciones = $habitaciones[1];
        echo <<< HTML
            <main>
        HTML;
        while ($fila = $habitaciones->fetch_assoc()) {
            [$resultado, $fotografias] = getFotosHabitacion($conexion, $fila["Habitacion"]);
            echo <<< HTML
                <section class="tarjeta">
                    <h2>Habitación {$fila["Habitacion"]}</h2>
                    <section>
                        <p>{$fila["Descripcion"]}</p>
                        <ul>
                            <li>Capacidad: {$fila["Capacidad"]} personas</li>
                            <li>Precio: {$fila["Precio"]} €/noche</li>
                        </ul>
                    </section>
                    <section>
                        <h2>Observa la Habitación</h2>
                        <div class="galeria">
            HTML;
            if ($resultado) {
                if (!empty($fotografias)) {
                    foreach ($fotografias as $foto) {
                        $imagen = $foto["Imagen"];
                        echo "<div class='imagen'>";
                        echo "<img src='data:image/jpeg;base64,$imagen'>";
                        echo "</div>";
                    }
                }
            }
            echo "</div>";
            echo "</section>";
            echo "</section>";
        }
        if ($habitaciones->num_rows == 0) {
            echo "<h2>No hay habitaciones registradas</h2>";
        }
        echo "</main>";
    }
}

function HTML_pagina_servicios()
{
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

function HTML_form_registro()
{ ?>
    <main>
        <section class="registro-usuarios">
            <h2>Registro de Usuarios</h2>
            <?php if (isset($_SESSION["datos-registro"]["correcto"]))  echo "<h2 class='datos-recibidos'>Los datos se han recibido correctamente</h2>" ?>
            <?php if (isset($_SESSION["datos-registro"]["correcto"])) $disable = "disabled";
            else $disable = ""; ?>
            <form action="" method="post" novalidate>
                <fieldset>
                    <legend>Datos del Usuario</legend>
                    <p>
                        <label for="idnombre">Nombre:</label>
                        <input type="text" id="idnombre" name="nombre" placeholder="(Obligatorio)" value=<?php if (isset($_SESSION["datos-registro"]["nombre"])) echo $_SESSION["datos-registro"]["nombre"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["nombre"])) echo $_SESSION["errores-registro"]["nombre"] ?>
                    <p>
                        <label for="idapellidos">Apellidos:</label>
                        <input type="text" id="idapellidos" name="apellidos" placeholder="(Obligatorio)" value=<?php if (isset($_SESSION["datos-registro"]["apellidos"])) echo $_SESSION["datos-registro"]["apellidos"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["apellidos"])) echo $_SESSION["errores-registro"]["apellidos"] ?>
                    <p>
                        <label for="iddni">DNI:</label>
                        <input type="text" id="iddni" name="dni" placeholder="(Solo DNIs Españoles)" value=<?php if (isset($_SESSION["datos-registro"]["dni"])) echo $_SESSION["datos-registro"]["dni"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["dni"])) echo $_SESSION["errores-registro"]["dni"] ?>
                </fieldset>
                <fieldset>
                    <legend>Datos de la Cuenta</legend>
                    <p>
                        <label for="idemail">Email:</label>
                        <input type="email" id="idemail" name="email" placeholder="(Obligatorio)" value=<?php if (isset($_SESSION["datos-registro"]["email"])) echo $_SESSION["datos-registro"]["email"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["email"])) echo $_SESSION["errores-registro"]["email"] ?>
                    <p>
                        <label for="idpassword">Contraseña:</label>
                        <input type="password" id="idpassword" name="clave" placeholder="(Mínimo 5 caracteres)" value=<?php if (isset($_SESSION["datos-registro"]["clave"])) echo $_SESSION["datos-registro"]["clave"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["clave"])) echo $_SESSION["errores-registro"]["clave"] ?>
                    <p>
                        <label for="idpassword2">Repetir Contraseña:</label>
                        <input type="password" id="idpassword2" name="clave-repetida" placeholder="(Repetir contraseña)" value=<?php if (isset($_SESSION["datos-registro"]["clave-repetida"])) echo $_SESSION["datos-registro"]["clave-repetida"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["clave-repetida"])) echo $_SESSION["errores-registro"]["clave-repetida"] ?>
                </fieldset>
                <fieldset>
                    <legend>Datos de Pago</legend>
                    <p>
                        <label for="idtarjeta">Tarjeta de Crédito:</label>
                        <input type="text" id="idtarjeta" name="tarjeta" placeholder="(16 dígitos)" value=<?php if (isset($_SESSION["datos-registro"]["tarjeta"])) echo $_SESSION["datos-registro"]["tarjeta"] ?> <?php echo $disable ?>>
                    </p>
                    <?php if (isset($_SESSION["errores-registro"]["tarjeta"])) echo $_SESSION["errores-registro"]["tarjeta"] ?>
                </fieldset>
                <div class="boton">
                    <?php
                    if (isset($_SESSION["datos-registro"]["correcto"])) {
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
function HTML_error_path()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>La página solicitada no existe</h2>
            </div>
        </main>
    HTML;
}

function HTML_error_permisos()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>No tienes permisos para acceder a la página solicitada</h2>
            </div>
        </main>
    HTML;
}

function HTML_form_habitaciones()
{ ?>
    <section class="registro-habitaciones">
        <?php if (isset($_SESSION["datos-habitacion"]["correcto"]))  echo "<h2 class='datos-recibidos'>Los datos se han recibido correctamente</h2>" ?>
        <?php if (isset($_SESSION["datos-habitacion"]["correcto"])) $disable = "disabled";
        else $disable = ""; ?>
        <form action="" id="formulario-habitaciones" method="post" enctype="multipart/form-data" novalidate>
            <fieldset>
                <legend>Registro de habitaciones</legend>
                <p>
                    <label for="idhabitacion">Nº Habitación:</label>
                    <input type="text" id="idhabitacion" name="habitacion" value=<?php if (isset($_SESSION["datos-habitacion"]["habitacion"])) echo $_SESSION["datos-habitacion"]["habitacion"] ?> <?php echo $disable ?>>
                </p>
                <?php if (isset($_SESSION["errores-habitacion"]["habitacion"])) echo $_SESSION["errores-habitacion"]["habitacion"] ?>
                <p>
                    <label for="idcapacidad">Capacidad:</label>
                    <input type="text" id="idcapacidad" name="capacidad" value=<?php if (isset($_SESSION["datos-habitacion"]["capacidad"])) echo $_SESSION["datos-habitacion"]["capacidad"] ?> <?php echo $disable ?>>
                </p>
                <?php if (isset($_SESSION["errores-habitacion"]["capacidad"])) echo $_SESSION["errores-habitacion"]["capacidad"] ?>
                <p>
                    <label for="idprecio">Precio:</label>
                    <input type="text" id="idprecio" name="precio" value=<?php if (isset($_SESSION["datos-habitacion"]["precio"])) echo $_SESSION["datos-habitacion"]["precio"] ?> <?php echo $disable ?>>
                </p>
                <?php if (isset($_SESSION["errores-habitacion"]["precio"])) echo $_SESSION["errores-habitacion"]["precio"] ?>
                <p>
                    <label for="iddescripcion">Descripción:</label>
                    <input type="text" id="iddescripcion" name="descripcion" value=<?php if (isset($_SESSION["datos-habitacion"]["descripcion"])) echo $_SESSION["datos-habitacion"]["descripcion"] ?> <?php echo $disable ?>>
                </p>
                <?php if (isset($_SESSION["errores-habitacion"]["descripcion"])) echo $_SESSION["errores-habitacion"]["descripcion"] ?>
                <p>
                    <label for="foto">Fotografía:</label>
                    <input type="file" id="foto" name="fotos[]" multiple <?php echo $disable ?> <?php if(isset($_SESSION["modificar-habitacion"]) && $_SESSION["modificar-habitacion"]) echo "disabled" ?>>
                </p>
                <?php if(isset($_SESSION["errores-habitacion"]["fotos"])) echo $_SESSION["errores-habitacion"]["fotos"] ?>
                <div id="previsualizaciones"></div>
                <?php if (isset($_SESSION["datos-habitacion"]["fotos"]) && is_array($_SESSION["datos-habitacion"]["fotos"])) {
                    foreach ($_SESSION["datos-habitacion"]["fotos"] as $index => $imagen_base64) {
                        echo "<img src='data:image/jpeg;base64,$imagen_base64' style='max-width: 350px; height:auto;'>";
                        echo "<input type='hidden' name='fotos_guardadas[]' value='$imagen_base64''>";
                    }
                } ?>
            </fieldset>
            <div class="boton">
                <?php
                if (isset($_SESSION["datos-habitacion"]["correcto"])) {
                    echo "<input type='submit' value='Confirmar Datos' name='confirmar-habitacion' id='boton-enviar'>";
                } else {
                    if (isset($_SESSION["modificar-habitacion"])) {
                        echo "<input type='submit' value='Modificar Datos' name='enviar-modificar-habitacion' id='boton-enviar'>";
                    } else {
                        echo "<input type='submit' value='Enviar Datos' name='enviar-habitacion' id='boton-enviar'>";
                    }
                }
                ?>
            </div>
        </form>
    </section>

<?php }

function HTML_tabla_Habitaciones($conexion)
{ ?>
    <section class="listado-habitaciones">
        <table>
            <tr>
                <th>Nº Habitación</th>
                <th>Modificar</th>
                <th>Eliminar</th>
                <th>Modificar Imágenes</th>
            </tr>
            <?php
            $resultado = getHabitaciones($conexion);
            if ($resultado[0]) {
                $resultado = $resultado[1];
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["Habitacion"] . "</td>";
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='id-habitacion' value='" . $fila["id"] . "'>";
                    echo "<td><input type='submit' name='modificar-habitacion' value='Modificar'></td>";
                    echo "<td><input type='submit' name='borrar-habitacion' value='Borrar'></td>";
                    echo "<td><input type='submit' name='modificar-imagenes-habitacion' value='Modificar Imágenes'></td>";
                    echo "</form>";
                    echo "</tr>";
                }
                if ($resultado->num_rows == 0) {
                    echo "<tr><td colspan='4'>No hay habitaciones registradas</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay habitaciones registradas</td></tr>";
            }
            ?>
        </table>
    </section>
<?php }

function HTML_editar_fotos_habitacion()
{ ?>
    <form action="" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Subir Fotografías</legend>
            <p>
                <label for="foto">Fotografía:</label>
                <input type="file" id="foto" name="fotos[]" multiple>
            </p>
            <div id="previsualizaciones"></div>
            <div class="boton">
                <input type="submit" value="Enviar Fotos" name="enviar-fotos" id="boton-enviar">
            </div>
            <div class="listado-habitaciones"></div>
            </fieldset>
    </form>
    <section class="listado-habitaciones">
        <table>
            <tr>
                <th>Nº Habitación</th>
                <th>Imagen</th>
                <th>Eliminar</th>
            </tr>
            <?php
            if(!empty($_SESSION["fotos"])){
                foreach($_SESSION["fotos"] as $foto){
                    echo "<tr>";
                    echo "<td>" . $foto["Habitacion"] . "</td>";
                    echo "<td><img src='data:image/jpeg;base64," . $foto["Imagen"] . "' style='max-width:100px;'></td>";
                    echo "<td><form action='' method='post'><input type='hidden' name='id-foto' value='" . $foto["id"] . "'><input type='submit' name='borrar-foto' value='Borrar'></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay fotos registradas</td></tr>";
            }
            ?>
        </table>
    </section>
<?php }

function HTML_salir_edicion(){
    echo <<< HTML
        <form action="" method="post">
            <div class="boton">
                <input type="submit" value="Salir de la Edición" name="salir-edicion" id="boton-enviar">
            </div>
        </form>
    HTML;
}

function HTML_formulario_reserva(){ ?>
    <section class="registro-reserva">
        <form action="" method="post" novalidate>
            <fieldset>
                <legend>Reserva de Habitaciones</legend>
                <p>
                    <label for="idnumeropersonas">Número de Personas:</label>
                    <input type="text" id="idnumeropersonas" name="numeropersonas" value=<?php if (isset($_SESSION["datos-reserva"]["numeropersonas"])) echo $_SESSION["datos-reserva"]["numeropersonas"] ?>>
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["numeropersonas"])) echo $_SESSION["errores-reserva"]["numeropersonas"] ?>
                <p>
                    <label for="identrada">Fecha de Entrada:</label>
                    <input type="date" id="identrada" name="entrada" value=<?php if (isset($_SESSION["datos-reserva"]["entrada"])) echo $_SESSION["datos-reserva"]["entrada"] ?>>
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["entrada"])) echo $_SESSION["errores-reserva"]["entrada"] ?>
                <p>
                    <label for="idsalida">Fecha de Salida:</label>
                    <input type="date" id="idsalida" name="salida" value=<?php if (isset($_SESSION["datos-reserva"]["salida"])) echo $_SESSION["datos-reserva"]["salida"] ?>>
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["salida"])) echo $_SESSION["errores-reserva"]["salida"] ?>
                <p>
                    <label for="idcomentario">Comentario:</label>
                    <textarea id="idcomentario" name="comentario"><?php if (isset($_SESSION["datos-reserva"]["comentario"])) echo $_SESSION["datos-reserva"]["comentario"] ?></textarea>
                </p>
                <div class="boton">
                    <input type="submit" value="Enviar Reserva" name="enviar-reserva" id="boton-enviar">
                </div>
            </fieldset>
        </form>
    </section>
<?php }

function HTML_error_reserva()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>No hay habitaciones disponibles con dichas especificaciones</h2>
            </div>
        </main>
    HTML;
}

?>