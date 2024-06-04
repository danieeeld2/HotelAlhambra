<?php
require_once("funcionesAuxiliares.php");
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
            <?php if ($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Cliente") echo "<li><a href='index.php?pagina=reservas'>Reservar</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Recepcionista") echo "<li><a href='index.php?pagina=lista-reservas'>Gestión Reservas</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Cliente") echo "<li><a href='index.php?pagina=lista-reservas'>Mis Reservas</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Administrador") echo "<li><a href='index.php?pagina=lista-usuarios'>Gestión Usuarios</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Administrador") echo "<li><a href='index.php?pagina=lista-logs'>Ver Logs</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Administrador") echo "<li><a href='index.php?pagina=gestion-bd'>Gestión BD</a></li>" ?>
            <?php if ($_SESSION["rol"] == "Recepcionista") echo "<li><a href='index.php?pagina=gestion-reservas-opcional'>Tabla de Reservas</a></li>" ?>
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
            <input type="submit" value="Iniciar Sesión" name="iniciar-sesion" id="boton-login">
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

function HTML_cambiar_datos_usuario()
{ ?>
    <?php
        $mostrar_formulario = '';
        if (isset($_SESSION["errores-datos-usuario"]) && !empty($_SESSION["errores-datos-usuario"])) {
            $mostrar_formulario = 'block';
        } else {
            $mostrar_formulario = 'none';
        }
    ?>
    <div class="cambiar-datos-usuario">
        <button id="mostrar-formulario">Cambiar Datos de Usuario</button>
        <form id="formulario-cambiar-datos" method="post" style="display: <?php echo $mostrar_formulario ?>;" novalidate>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php if(isset($_SESSION["datos-usuario"]["email"])) echo $_SESSION["datos-usuario"]["email"] ?>"><br>
            <?php if(isset($_SESSION["errores-datos-usuario"]["email"])) echo $_SESSION["errores-datos-usuario"]["email"] ?>
            <label for="clave">Clave:</label>
            <input type="password" id="clave" name="clave" value="<?php if(isset($_SESSION["datos-usuario"]["clave"])) echo $_SESSION["datos-usuario"]["clave"] ?>"><br>
            <?php if(isset($_SESSION["errores-datos-usuario"]["clave"])) echo $_SESSION["errores-datos-usuario"]["clave"] ?>
            <label for="repetir-clave">Repetir Clave:</label>
            <input type="password" id="repetir-clave" name="clave-repetida" value="<?php if(isset($_SESSION["datos-usuario"]["clave-repetida"])) echo $_SESSION["datos-usuario"]["clave-repetida"] ?>"><br>
            <?php if(isset($_SESSION["errores-datos-usuario"]["clave-repetida"])) echo $_SESSION["errores-datos-usuario"]["clave-repetida"] ?>
            <label for="numero-tarjeta">Nº Tarjeta:</label>
            <input type="text" id="numero-tarjeta" name="tarjeta" value="<?php if(isset($_SESSION["datos-usuario"]["tarjeta"])) echo $_SESSION["datos-usuario"]["tarjeta"] ?>"><br>
            <?php if(isset($_SESSION["errores-datos-usuario"]["tarjeta"])) echo $_SESSION["errores-datos-usuario"]["tarjeta"] ?>
            <button type="submit" name="cambiar-datos-usuario">Actualizar</button>
        </form>
    </div>
    <?php
        if(isset($_SESSION["exito-cambio-datos-usuario"])) {
            echo '<script>alert("¡Datos actualizados correctamente!");</script>';
            unset($_SESSION["exito-cambio-datos-usuario"]); 
        }
    ?>
    <script src="formularioCambioDatos.js"></script>
<?php }

// Función para generar el aside
function HTML_aside($conexion)
{ ?>
    </div>
    <aside class="zona-lateral">
        <div class="inicio-sesion">
            <?php if (isset($_SESSION["iniciado-sesion"]) && !$_SESSION["iniciado-sesion"]) HTML_formulario_login();
            else if (isset($_SESSION["iniciado-sesion"]) && $_SESSION["iniciado-sesion"]) HTML_logout() ?>
        </div>
        <?php 
            if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "Anonimo"){
                HTML_cambiar_datos_usuario();
                echo "<section class='informacion-2nivel'>";
                echo "<h2>Información de Interés</h2>";
                echo "<ul>";
                if($_SESSION["rol"] == "Cliente"){
                    echo "<li>Nº Reservas Usuario:". contarReservasUsuario($conexion, $_SESSION["email"])[1]["count"] ."</li>";
                    $proxima_reserva = obtenerProximaReserva($conexion, $_SESSION["email"]);
                    if($proxima_reserva != null){
                        echo "<li>Próxima Reserva:". $proxima_reserva["Habitacion"] . ", " . $proxima_reserva["Entrada"] ."</li>";
                    }
                } else if($_SESSION["rol"] == "Recepcionista" || $_SESSION["rol"] == "Administrador"){
                    echo "<li>Nº Reservas Confirmadas:". contarReservasEstado($conexion, "Confirmada") ."</li>";
                    echo "<li>Nº Reservas Pendientes:". contarReservasEstado($conexion, "Pendiente") ."</li>";
                    echo "<li>Mantenimientos Programados:". contarReservasEstado($conexion, "Mantenimiento") ."</li>";
                    echo "<li>Nº Clientes:". contarClientes($conexion) ."</li>";
                    echo "<li>Nº Habutaciones:". contarHabitaciones($conexion) ."</li>";
                }
                echo "</ul>";
                echo "</section>";
            }
        ?>
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
                    <p>Daniel Alconchel & Juan Fernández</p>
                </div>
                <div>
                    <p><a href="documentacion.pdf">Documentación Proyecto</a></p>
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
            <form action="<?php if(isset($_SESSION["modificar-usuario"]) && $_SESSION["modificar-usuario"]) echo "index.php?pagina=registro"?>" method="post" novalidate>
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
                <?php if($_SESSION["rol"] == "Administrador") { ?>
                    <fieldset>
                        <legend>Rol</legend>
                        <p>
                            <label for="rol">Rol:</label>
                            <select id="rol" name="rol">
                                <option value="Cliente" <?php if(isset($_SESSION["datos-registro"]["rol"]) && $_SESSION["datos-registro"]["rol"] == "Cliente") echo "selected" ?>>Cliente</option>
                                <option value="Recepcionista" <?php if(isset($_SESSION["datos-registro"]["rol"]) && $_SESSION["datos-registro"]["rol"] == "Recepcionista") echo "selected" ?>>Recepcionista</option>
                                <option value="Administrador" <?php if(isset($_SESSION["datos-registro"]["rol"]) && $_SESSION["datos-registro"]["rol"] == "Administrador") echo "selected" ?>>Administrador</option>
                            </select>
                        </p>
                    </fieldset>
                <?php } ?>
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
                    <input type="file" id="foto" name="fotos[]" multiple <?php echo $disable ?> <?php if (isset($_SESSION["modificar-habitacion"]) && $_SESSION["modificar-habitacion"]) echo "disabled" ?>>
                </p>
                <?php if (isset($_SESSION["errores-habitacion"]["fotos"])) echo $_SESSION["errores-habitacion"]["fotos"] ?>
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
                    echo "<form action='' method='post' novalidate>";
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
    <form action="" method="post" enctype="multipart/form-data" novalidate>
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
            if (!empty($_SESSION["fotos"])) {
                foreach ($_SESSION["fotos"] as $foto) {
                    echo "<tr>";
                    echo "<td>" . $foto["Habitacion"] . "</td>";
                    echo "<td><img src='data:image/jpeg;base64," . $foto["Imagen"] . "' style='max-width:100px;'></td>";
                    echo "<td><form action='' method='post' novalidate><input type='hidden' name='id-foto' value='" . $foto["id"] . "'><input type='submit' name='borrar-foto' value='Borrar'></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay fotos registradas</td></tr>";
            }
            ?>
        </table>
    </section>
<?php }

function HTML_salir_edicion()
{
    echo <<< HTML
        <form action="" method="post" novalidate>
            <div class="boton">
                <input type="submit" value="Salir de la Edición" name="salir-edicion" id="boton-enviar">
            </div>
        </form>
    HTML;
}

function HTML_formulario_reserva($usuarios, $habitaciones)
{ ?>
    <section class="registro-reserva">
        <form action="" method="post" novalidate>
            <fieldset>
                <legend id="legend-reserva">Reserva de Habitaciones</legend>
                <legend id="legend-reforma" style="display: none;">Reforma de Habitaciones</legend>
                <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") { ?>
                    <p>
                        <input type="checkbox" id="idreforma" name="reforma">
                        <label for="idreforma" id="reforma-label">Establecer habitación en estado de reforma</label>
                    </p>
                    <p id="usuario-select-container">
                        <label for="idusuario">Seleccionar Usuario:</label>
                        <select name="usuario-reserva" id="idusuario">
                            <?php
                            if ($usuarios[0]) {
                                $usuarios = $usuarios[1];
                                while ($fila = $usuarios->fetch_assoc()) {
                                    echo "<option value='" . $fila["email"] . "'>" . $fila["nombre"] . " - " . $fila["apellidos"] . " - " . $fila["dni"] . " - " . $fila["email"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </p>
                    <p id="room-select-container" style="display: none;">
                        <label for="idhabitacion">Seleccionar Habitación:</label>
                        <select name="habitacion-reforma" id="idhabitacion">
                            <?php
                            if ($habitaciones) {
                                while ($habitacion = $habitaciones->fetch_assoc()) {
                                    echo "<option value='" . $habitacion["Habitacion"] . "'>" . $habitacion["Habitacion"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </p>
                    <?php if (isset($_SESSION["errores-reserva"]["habitacion-reforma"])) echo $_SESSION["errores-reserva"]["habitacion-reforma"]; ?>
                    <p id="reforma-marcada-container" style="display: none;">
                        <input type="hidden" name="enviar-reserva" value="1">
                    </p>
                <?php } ?>
                <p id="numeropersonas-container">
                    <label for="idnumeropersonas">Número de Personas:</label>
                    <input type="text" id="idnumeropersonas" name="numeropersonas" value="<?php if (isset($_SESSION["datos-reserva"]["numeropersonas"])) echo $_SESSION["datos-reserva"]["numeropersonas"]; ?>">
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["numeropersonas"])) echo $_SESSION["errores-reserva"]["numeropersonas"]; ?>
                <p>
                    <label for="identrada">Fecha de Entrada:</label>
                    <input type="date" id="identrada" name="entrada" value="<?php if (isset($_SESSION["datos-reserva"]["entrada"])) echo $_SESSION["datos-reserva"]["entrada"]; ?>">
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["entrada"])) echo $_SESSION["errores-reserva"]["entrada"]; ?>
                <p>
                    <label for="idsalida">Fecha de Salida:</label>
                    <input type="date" id="idsalida" name="salida" value="<?php if (isset($_SESSION["datos-reserva"]["salida"])) echo $_SESSION["datos-reserva"]["salida"]; ?>">
                </p>
                <?php if (isset($_SESSION["errores-reserva"]["salida"])) echo $_SESSION["errores-reserva"]["salida"]; ?>
                <p id="comentario-container">
                    <label for="idcomentario">Comentario:</label>
                    <textarea id="idcomentario" name="comentario"><?php if (isset($_SESSION["datos-reserva"]["comentario"])) echo $_SESSION["datos-reserva"]["comentario"]; ?></textarea>
                </p>
                <div class="boton">
                    <input type="submit" value="Enviar Reserva" name="enviar-reserva" id="boton-enviar">
                </div>
            </fieldset>
        </form>
    </section>
    <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") echo "<script src='formularioHabitaciones.js'></script>" ?>
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

function HTML_confirmar_reserva($datos_reserva, $email)
{ ?>
    <section class="tarjeta">
        <h2>Confirmación de Reserva</h2>
        <section>
            <p>Se ha realizado la reserva de la habitación <?php echo $datos_reserva["Habitacion"] ?> con éxito. A continuación, se muestran los detalles de la reserva:</p>
            <ul>
                <li>Número de Personas: <?php echo $datos_reserva["Personas"] ?></li>
                <li>Fecha de Entrada: <?php echo $datos_reserva["Entrada"] ?></li>
                <li>Fecha de Salida: <?php echo $datos_reserva["Salida"] ?></li>
                <li>Comentario: <?php echo $datos_reserva["Comentario"] ?></li>
                <li>Precio: <?php echo $datos_reserva["Precio"] ?> €/noche</li>
                <li>Email de Reserva: <?php echo $email ?></li>
            </ul>
        </section>
        <form action="" method="post" novalidate>
            <div class="fila-boton">
                <div class="boton">
                    <input type="submit" value="Confirmar Reserva" name="confirmar-reserva" id="boton-enviar">
                </div>
                <div class="boton">
                    <input type="submit" value="Cancelar Reserva" name="cancelar-reserva" id="boton-enviar">
                </div>
            </div>
        </form>
    </section>
<?php }

function HTML_error_reserva_expirada()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>Excediste el tiempo de espera, reserva expirada</h2>
            </div>
        </main>
    HTML;
}

function HTML_error_mantenimiento()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>La habitación está reservada para esa fecha (Reubique primero al cliente)</h2>
            </div>
        </main>
    HTML;
}

function HTML_error_mantenimiento_confirmado()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>Hubo un error durante la creación del mantenimiento</h2>
            </div>
        </main>
    HTML;
}

function HTML_success_mantenimiento_confirmado()
{
    echo <<< HTML
        <main>
            <div class="success-path">
                <h2>Se estableció el mantenimiento con éxito</h2>
            </div>
        </main>
    HTML;
}

function HTML_gestion_reservas($conexion)
{ ?>
    <form action="" method="post" novalidate>
        <?php 
        $valores_cookie = explode(",", $_COOKIE["filtros-reserva"]);
        ?>
        <fieldset>
            <legend>Filtros</legend>
            <p>
                <label id="label-paginacion" for="idpaginacion">Número de Reservas a Mostrar:</label>
                <input type="number" id="idpaginacion" name="paginacion" value="<?php echo $valores_cookie[0] ?>">
            </p>
            <p>
                <label for="ordenamiento">Ordenar por:</label>
                <select id="ordenamiento" name="ordenamiento">
                    <option value="antiguedad_asc" <?php if($valores_cookie[1] == "antiguedad_asc") echo "selected" ?>>Antigüedad (ascendente)</option>
                    <option value="antiguedad_desc" <?php if($valores_cookie[1] == "antiguedad_desc") echo "selected" ?>>Antigüedad (descendente)</option>
                    <option value="duracion_asc" <?php if($valores_cookie[1] == "duracion_asc") echo "selected" ?>>Duración de la reserva (ascendente)</option>
                    <option value="duracion_desc" <?php if($valores_cookie[1] == "duracion_desc") echo "selected" ?>>Duración de la reserva (descendente)</option>
                </select>
            </p>
            <fieldset class="rango-fechas">
                <legend>Rango de Fechas</legend>
                <p>
                    <label for="fecha_inicio">Fecha de Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $valores_cookie[2] ?>">
                </p>
                <p>
                    <label for="fecha_fin">Fecha de Fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $valores_cookie[3] ?>">
                </p>
            </fieldset>
            <p>
                <label for="comentario">Texto en Comentario:</label>
                <input type="text" id="comentario" name="comentario" value="<?php echo $valores_cookie[4] ?>">
            </p>
            <div class="boton">
                <input type="submit" value="Aplicar Filtros" name="filtros-reservas" id="boton-enviar">
            </div>
        </fieldset>
    </form>
    <section class="listado-reservas">
        <table>
            <tr>
                <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") echo "<th>Usuario</th>"; ?>
                <th>Habitación</th>
                <th>Nº Personas</th>
                <th>Fecha de Entrada</th>
                <th>Fecha de Salida</th>
                <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") echo "<th>Estado</th>"; ?>
                <th>Precio</th>
                <th>Comentario</th>
                <th>Modificar Comentario</th>
                <th>Eliminar</th>
            </tr>
            <?php
            $total_paginas = 0;
            if (isset($_SESSION["rol"])) {
                if ($_SESSION["rol"] == "Recepcionista") {
                    $email="";
                } else {
                    $email=$_SESSION["email"];
                }
                $numero_tuplas = contarTuplasFiltro($conexion, $valores_cookie[4], $valores_cookie[2], $valores_cookie[3], $email);
                $total_paginas = ceil($numero_tuplas["count"] / $valores_cookie[0]);
                if (isset($_GET["pagina_actual"])) {
                    if ($_GET["pagina_actual"] > 0 && $_GET["pagina_actual"] <= $total_paginas) {
                        $pagina_actual = $_GET["pagina_actual"];
                    } else {
                        $pagina_actual = 1;
                    }
                } else {
                    $pagina_actual = 1;
                }
                $offset = ($pagina_actual - 1) * $valores_cookie[0];
                $reservas = getReservasConFiltro($conexion, $offset, $valores_cookie[0], $valores_cookie[1], $valores_cookie[4], $valores_cookie[2], $valores_cookie[3], $email);
                if ($reservas[0]) {
                    $reservas = $reservas[1];
                    foreach ($reservas as $fila) {
                        echo "<tr>";
                        if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") echo "<td>" . $fila["email"] . "</td>";
                        echo "<td class='habitacion' title=" .$fila["Marca"] .">" . $fila["Habitacion"] . "</td>";
                        echo "<td>" . $fila["Personas"] . "</td>";
                        echo "<td>" . $fila["Entrada"] . "</td>";
                        echo "<td>" . $fila["Salida"] . "</td>";
                        if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "Recepcionista") echo "<td>" . $fila["Estado"] . "</td>";
                        echo "<td>" . $fila["Precio"] . "</td>";
                        echo "<form action='' method='post' novalidate>";
                        echo "<td class='comentario'>" . $fila["Comentario"] . "</td>";
                        echo "<td class='nuevo-comentario' style='display:none;'><input type='text' name='nuevo-comentario'></td>";
                        echo "<input type='hidden' name='id-reserva' value='" . $fila["id"] . "'>";
                        echo "<td><button type='button' class='editar-comentario'>Editar</button><input type='submit' name='modificar-comentario' value='Modificar Comentario' class='modificar-comentario' style='display:none;'></td>";
                        echo "<td><input type='submit' name='borrar-reserva' value='Borrar'></td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                } else {
                    if ($_SESSION["rol"] == "Recepcionista") {
                        echo "<tr><td colspan='10'>No hay reservas registradas</td></tr>";
                    } else {
                        echo "<tr><td colspan='8'>No hay reservas registradas</td></tr>";
                    }
                }
            }
            ?>
        </table>
    </section>
    <div class="paginacion">
            <?php
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<a href='index.php?pagina=lista-reservas&pagina_actual=$i'>$i</a>";
            }
            ?>
    </div>
    <?php if(isset($_GET["pagina"]) && $_GET["pagina"] == "lista-reservas") echo "<script src='tablaReservas.js'></script>"; ?>
<?php }

function HTML_gestion_usuarios($conexion) { ?>
    <form action="" method="post" novalidate>
        <?php 
            $valores_cookie = explode(",", $_COOKIE["filtros-usuarios"]);
        ?>
        <fieldset>
            <legend>Filtros</legend>
            <p>
                <label id="label-paginacion" for="idpaginacion">Número de Usuarios a Mostrar:</label>
                <input type="number" id="idpaginacion" name="paginacion" value="<?php echo $valores_cookie[0] ?>">
            </p>
            <p>
                <label for="iddni">DNI:</label>
                <input type="text" id="iddni" name="dni" value="<?php echo $valores_cookie[1] ?>">
            </p>
            <p>
                <label for="idemail">Email:</label>
                <input type="email" id="idemail" name="email" value="<?php echo $valores_cookie[2] ?>">
            </p>
            <?php if($_SESSION["rol"] == "Administrador") { ?>
                <p>
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol">
                        <option value="Todos" <?php if($valores_cookie[3] == "") echo "selected" ?>>Todos</option>
                        <option value="Cliente" <?php if($valores_cookie[3] == "Cliente") echo "selected" ?>>Cliente</option>
                        <option value="Recepcionista" <?php if($valores_cookie[3] == "Recepcionista") echo "selected" ?>>Recepcionista</option>
                        <option value="Administrador" <?php if($valores_cookie[3] == "Administrador") echo "selected" ?>>Administrador</option>
                    </select>
                </p>
            <?php } ?>
            <div class="boton">
                <input type="submit" value="Aplicar Filtros" name="filtros-usuarios" id="boton-enviar">
            </div>
        </fieldset>
    </form>
    <section class="listado-usuarios">
        <table>
            <tr>
                <?php if($_SESSION["rol"] == "Administrador") echo "<th>Rol</th>" ?>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
            <?php
                $total_paginas = 0;
                if($_SESSION["rol"] == "Recepcionista") {
                    $numero_tuplas = contarTuplasFiltroUsuarios($conexion, $valores_cookie[1], $valores_cookie[2], "Cliente");
                } else {
                    $numero_tuplas = contarTuplasFiltroUsuarios($conexion, $valores_cookie[1], $valores_cookie[2], $valores_cookie[3]);
                }
                $total_paginas = ceil($numero_tuplas["count"] / $valores_cookie[0]);
                if (isset($_GET["pagina_actual"])) {
                    if ($_GET["pagina_actual"] > 0 && $_GET["pagina_actual"] <= $total_paginas) {
                        $pagina_actual = $_GET["pagina_actual"];
                    } else {
                        $pagina_actual = 1;
                    }
                } else {
                    $pagina_actual = 1;
                }
                $offset = ($pagina_actual - 1) * $valores_cookie[0];
                if($_SESSION["rol"] == "Recepcionista") {
                    $usuarios = obtenerUsuariosFiltro($conexion,  $offset, $valores_cookie[0], $valores_cookie[1], $valores_cookie[2], "Cliente");
                } else {
                    $usuarios = obtenerUsuariosFiltro($conexion,  $offset, $valores_cookie[0], $valores_cookie[1], $valores_cookie[2], $valores_cookie[3]);
                }
                if($usuarios[0]){
                    $usuarios = $usuarios[1];
                    foreach($usuarios as $fila){
                        echo "<tr>";
                        if($_SESSION["rol"] == "Administrador") echo "<td>". $fila["rol"] ."</td>";
                        echo "<td>". $fila["nombre"] ."</td>";
                        echo "<td>". $fila["apellidos"] ."</td>";
                        echo "<td>". $fila["dni"] ."</td>";
                        echo "<td>". $fila["email"] ."</td>";
                        echo "<form action='' method='post' novalidate>";
                        echo "<input type='hidden' name='id-usuario' value='" . $fila["id"] . "'>";
                        echo "<td><input type='submit' name='modificar-usuario' value='Modificar Usuario'></td>";
                        echo "<td><input type='submit' name='borrar-usuario' value='Borrar'></td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                } else {
                    if($_SESSION["rol"] == "Recepcionista"){
                        echo "<tr><td colspan='6'>No hay clientes registrados con dichas especificaciones</td></tr>";
                    } else {
                        echo "<tr><td colspan='7'>No hay usuarios registrados con dichas especificaciones</td></tr>";
                    }
                }
            ?>
        </table>
    </section>
    <div class="paginacion">
        <?php
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<a href='index.php?pagina=lista-usuarios&pagina_actual=$i'>$i</a>";
            }
        ?>
    </div>
    <div class="paginacion">
        <a href="index.php?pagina=registro">
            <?php
                if($_SESSION["rol"] == "Recepcionista"){
                    echo "Crear nuevo Cliente";
                } else {
                    echo "Crear nuevo Usuario";
                }
            ?>
        </a>
    </div>
<?php }

function HTML_gestion_logs($conexion){ ?>
    <form action="" method="post" novalidate>
        <?php 
            $valores_cookie = explode(",", $_COOKIE["filtros-logs"]);
        ?>
        <fieldset>
            <legend>Filtros de Logs</legend>
            <p>
                <label id="label-paginacion" for="idpaginacion">Número de Logs a Mostrar:</label>
                <input type="number" id="idpaginacion" name="paginacion" value="<?php echo $valores_cookie[0] ?>">
            </p>
            <?php
                $tipos_logs = getTipoLogs($conexion);
                if($tipos_logs[0]){
                    $tipos_logs = $tipos_logs[1];
                    echo "<p>";
                    echo "<label for='idtipo'>Tipo de Log:</label>";
                    echo "<select id='idtipo' name='tipo'>";
                    echo "<option value='Todos' ". ($valores_cookie[1] == "" ? "selected" : "") .">Todos</option>";
                    foreach($tipos_logs as $tipo){
                        echo "<option value='". $tipo ."' ". ($valores_cookie[1] == $tipo ? "selected" : "") .">". $tipo ."</option>";
                    }
                    echo "</select>";
                    echo "</p>";
                }
            ?>
            <div class="boton">
                <input type="submit" value="Aplicar Filtros" name="filtros-logs" id="boton-enviar">
            </div>
        </fieldset>
    </form>
    <section class="listado-logs">
        <table>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Tipo</th>
            </tr>
            <?php 
                $total_paginas = 0;
                $numero_tuplas = contarLogsTipo($conexion, $valores_cookie[1]);
                $total_paginas = ceil($numero_tuplas["count"] / $valores_cookie[0]);
                if (isset($_GET["pagina_actual"])) {
                    if ($_GET["pagina_actual"] > 0 && $_GET["pagina_actual"] <= $total_paginas) {
                        $pagina_actual = $_GET["pagina_actual"];
                    } else {
                        $pagina_actual = 1;
                    }
                } else {
                    $pagina_actual = 1;
                }
                $offset = ($pagina_actual - 1) * $valores_cookie[0];
                $logs = obtenerLogsFiltro($conexion, $offset, $valores_cookie[0], $valores_cookie[1]);
                if($logs[0]){
                    $logs = $logs[1];
                    foreach($logs as $fila){
                        echo "<tr>";
                        echo "<td>". date("d-m-Y H:i:s", $fila["MarcaTemporal"]) ."</td>";
                        echo "<td>". $fila["Descripcion"] ."</td>";
                        echo "<td>". $fila["Tipo"] ."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay logs registrados con dichas especificaciones</td></tr>";
                }
            ?>
        </table>
    </section>
    <div class="paginacion">
        <?php
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<a href='index.php?pagina=lista-logs&pagina_actual=$i'>$i</a>";
            }
        ?>
    </div>
<?php }

function HTML_gestion_BD() { ?>
    <form action="" method="post" enctype="multipart/form-data" novalidate>
        <fieldset>
            <legend>Gestión de la Base de Datos</legend>
            <div class="grupo-botones">
                <div class="boton">
                    <input type="submit" value="Crear Backup" name="crear-backup" id="boton-enviar">
                </div>
                <input type="file" name="backup" id="backup">
                <?php if(isset($_SESSION["errores-backup"]["backup"])) echo $_SESSION["errores-backup"]["backup"]; ?>
                <div class="boton">
                    <input type="submit" value="Restaurar Backup" name="restaurar-backup" id="boton-enviar">
                </div>
                <div class="boton">
                    <input type="submit" value="Reiniciar BD" name="reiniciar-bd" id="boton-enviar">
                </div>
            </div>
        </fieldset>
    </form>
<?php }

function HTML_error_backup()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>Hubo un error durante la creación del backup</h2>
            </div>
        </main>
    HTML;
}

function HTML_success_backup()
{
    echo <<< HTML
        <main>
            <div class="success-path">
                <h2>Se creó el backup con éxito</h2>
            </div>
        </main>
    HTML;
}

function HTML_error_reiniciarBD()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>Hubo un error durante el reinicion de la BD</h2>
            </div>
        </main>
    HTML;
}

function HTML_success_reiniciarBD()
{
    echo <<< HTML
        <main>
            <div class="success-path">
                <h2>Se reinció la BD con éxito</h2>
            </div>
        </main>
    HTML;
}

function HTML_error_restaurar()
{
    echo <<< HTML
        <main>
            <div class="error-path">
                <h2>Hubo un error durante la restauración del backup</h2>
            </div>
        </main>
    HTML;
}

function HTML_success_restaurar()
{
    echo <<< HTML
        <main>
            <div class="success-path">
                <h2>Backup restaurado con éxito</h2>
            </div>
        </main>
    HTML;
}

function HTML_tabla_opcional_reservas($conexion) { ?>
    <main class="tabla-reserva">
                <div class="table-header">
                    <span class="botones-header">
                        Habitaciones
                        <a href="index.php?pagina=gestion-habitaciones">+</a>
                    </span>
                    <span class="botones-header">
                        Reservas
                        <a href="index.php?pagina=reservas">+</a>
                    </span>
                </div>
                <div class="table-body">
                    <span>Nº Hab.</span>
                    <span class="oculto">Na</span>
                    <span>Cap.</span>
                    <span>Hoy</span>
                    <span class="rowspan" id="boton-menos1">
                        <img src="img/menos1d.png" alt="-1d" width="20" height="20">
                    </span>
                    <?php for ($i = 1; $i < 30; $i++): ?>
                        <span class="dia" data-dia="<?php echo $i; ?>">
                            <?php echo date("d-m", strtotime("+$i days")); ?>
                        </span>
                    <?php endfor; ?>
                    <span class="rowspan" id="boton-mas1">
                        <img src="img/mas1d.png" alt="-1d" width="20" height="20">
                    </span>
                </div>
                <?php
                    $habitaciones = getHabitaciones($conexion);
                    if($habitaciones[0]){
                        $habitaciones = $habitaciones[1];
                        while($habitacion = $habitaciones->fetch_assoc()){
                            echo "<div class='table-body'>";
                            echo "<span class='nombre-habitacion'>". $habitacion["Habitacion"] ."</span>";
                            echo "<form action='' method='post' novalidate>";
                            echo "<span class='botones-grid'>";
                            echo "<input type='hidden' name='id-habitacion' value='". $habitacion["id"] ."'>";
                            echo "<input type='submit' name='borrar-habitacion' value='Borr.'>";
                            echo "<input type='submit' name='modificar-habitacion' value='Mod.'>";
                            echo "<input type='submit' name='modificar-imagenes-habitacion' value='Imgs.'>";
                            echo "</span>";
                            echo "</form>";
                            echo "<span>". $habitacion["Capacidad"] ."</span>";
                            $reserva_hoy = obtenerReservasHabitacionFecha($conexion, $habitacion["Habitacion"], date("Y-m-d"));
                            if(!empty($reserva_hoy)){
                                echo "<span>". getLetraReservas($reserva_hoy[0]["Estado"]) ."</span>";
                            } else {
                                echo "<span class='oculto'>". "Na" ."</span>";
                            }
                            echo "<span class='rowspan' id='boton-menos1'><img src='img/menos1d.png' alt='-1d' width='20' height='20'></span>";
                            for ($i = 1; $i < 30; $i++) {
                                $fecha = date("Y-m-d", strtotime("+$i days"));
                                $reserva = obtenerReservasHabitacionFecha($conexion, $habitacion["Habitacion"], $fecha);
                                if (!empty($reserva)) {
                                    echo "<span class='dia' data-dia='$i' id='dia-{$habitacion["id"]}-{$i}'>" . getLetraReservas($reserva[0]["Estado"]) . "</span>";
                                } else {
                                    echo "<span class='dia' data-dia='$i' id='dia-{$habitacion["id"]}-{$i}'>Na</span>";
                                }
                            }
                            echo "<span class='rowspan' id='boton-mas1'><img src='img/mas1d.png' alt='-1d' width='20' height='20'></span>";
                            echo "</div>";
                        }
                    }
                ?>
                <div class="table-fila">
                    <span>Plazas</span>
                </div>
                <div class="table-footer">
                    <span>Total</span>
                    <?php
                        $total_reservas = obtenerTotalReservasFecha($conexion, date("Y-m-d"));
                        if(!empty($total_reservas) || $total_reservas == 0){
                            echo "<span>". $total_reservas."</span>";
                        } else {
                            echo "<span>Error</span>";
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-menos1'><img src='img/menos1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                    <?php
                        for ($i = 1; $i < 30; $i++) {
                            $fecha = date("Y-m-d", strtotime("+$i days"));
                            $total_reservas = obtenerTotalReservasFecha($conexion, $fecha);
                            if (!empty($total_reservas) || $total_reservas == 0) {
                                echo "<span class='dia' data-dia='$i'>". $total_reservas ."</span>";
                            } else {
                                echo "<span class='dia' data-dia='$i'>Error</span>";
                            }
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-mas1'><img src='img/mas1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                </div>
                <div class="table-footer">
                    <span>Usadas</span>
                    <?php
                        $confirmadas = obtenerTotalConfirmadasFecha($conexion, date("Y-m-d"));
                        if(!empty($confirmadas) || $confirmadas == 0){
                            echo "<span>". $confirmadas."</span>";
                        } else {
                            echo "<span>Error</span>";
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-menos1'><img src='img/menos1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                    <?php
                        for ($i = 1; $i < 30; $i++) {
                            $fecha = date("Y-m-d", strtotime("+$i days"));
                            $confirmadas = obtenerTotalConfirmadasFecha($conexion, $fecha);
                            if (!empty($confirmadas) || $confirmadas == 0) {
                                echo "<span class='dia' data-dia='$i'>". $confirmadas ."</span>";
                            } else {
                                echo "<span class='dia' data-dia='$i'>Error</span>";
                            }
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-mas1'><img src='img/mas1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                </div>
                <div class="table-footer">
                    <span>Mantenimiento</span>
                    <?php
                        $confirmadas = obtenerTotalMantenimientoFecha($conexion, date("Y-m-d"));
                        if(!empty($confirmadas) || $confirmadas == 0){
                            echo "<span>". $confirmadas."</span>";
                        } else {
                            echo "<span>Error</span>";
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-menos1'><img src='img/menos1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                    <?php
                        for ($i = 1; $i < 30; $i++) {
                            $fecha = date("Y-m-d", strtotime("+$i days"));
                            $confirmadas = obtenerTotalMantenimientoFecha($conexion, $fecha);
                            if (!empty($confirmadas) || $confirmadas == 0) {
                                echo "<span class='dia' data-dia='$i'>". $confirmadas ."</span>";
                            } else {
                                echo "<span class='dia' data-dia='$i'>Error</span>";
                            }
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-mas1'><img src='img/mas1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                </div>
                <div class="table-footer">
                    <span>Libres</span>
                    <?php
                        $confirmadas = contarNumeroHabitaciones($conexion)-obtenerTotalReservasFecha($conexion, date("Y-m-d"));
                        if(!empty($confirmadas) || $confirmadas == 0){
                            echo "<span>". $confirmadas."</span>";
                        } else {
                            echo "<span>Error</span>";
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-menos1'><img src='img/menos1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                    <?php
                        for ($i = 1; $i < 30; $i++) {
                            $fecha = date("Y-m-d", strtotime("+$i days"));
                            $confirmadas = contarNumeroHabitaciones($conexion)-obtenerTotalReservasFecha($conexion, $fecha);
                            if (!empty($confirmadas) || $confirmadas == 0) {
                                echo "<span class='dia' data-dia='$i'>". $confirmadas ."</span>";
                            } else {
                                echo "<span class='dia' data-dia='$i'>Error</span>";
                            }
                        }
                    ?>
                    <?php echo "<span class='rowspan' id='boton-mas1'><img src='img/mas1d.png' alt='-1d' width='20' height='20'></span>"; ?>
                </div>
            </main>
            <script src="tablaOpcional.js"></script>
<?php }

?>