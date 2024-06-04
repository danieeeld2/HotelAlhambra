<?php 
require_once('credenciales.php');
require_once("funcionesBD.php");
// Función para validar que la letra del DNI es correcta
function validarLetraDNI($dni) {
    $letra = substr($dni, -1);
    $numeros = substr($dni, 0, -1);
    $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    $posicion = $numeros % 23;
    $letraCorrecta = $letras[$posicion];
    if($letraCorrecta == $letra) return true;
    else return false;
}

// Función para validar tarjeta de crédito mediante el algoritmo de Luhn
function validarTarjeta($tarjeta) {
    $tarjeta = str_replace(" ", "", $tarjeta);
    $numeros = str_split($tarjeta);
    $numeros = array_reverse($numeros);
    $suma = 0;
    for($i = 0; $i < count($numeros); $i++) {
        if($i % 2 != 0) {
            $numeros[$i] *= 2;
            if($numeros[$i] > 9) {
                $numeros[$i] -= 9;
            }
        }
        $suma += $numeros[$i];
    }
    if($suma % 10 == 0) return true;
    else return false;
}

function checkInyection($campo) {
    if (preg_match('/[\'\"=<>]/', $campo)) {
        return ""; // El campo contiene caracteres sospechosos de inyección SQL
    }
    // Eliminar etiquetas HTML y JavaScript sospechosas
    $campo_limpiado = htmlspecialchars($campo, ENT_QUOTES | ENT_HTML5);
    if($campo_limpiado == $campo) {
        return $campo;
    }
    return "";
}

function limpiarSesionFormularios(){
    if(isset($_SESSION["errores-registro"])){
        unset($_SESSION["errores-registro"]);
    }
    if(isset($_SESSION["datos-registro"])){
        unset($_SESSION["datos-registro"]);
    }
    if(isset($_SESSION["errores-habitacion"])){
        unset($_SESSION["errores-habitacion"]);
    }
    if(isset($_SESSION["datos-habitacion"])){
        unset($_SESSION["datos-habitacion"]);
    }
    if(isset($_SESSION["modificar-habitacion"])){
        unset($_SESSION["modificar-habitacion"]);
    }
    if(isset($_SESSION["modificar-imagen-habitacion"])){
        unset($_SESSION["modificar-imagen-habitacion"]);
    }
    if(isset($_SESSION["fotos"])){
        unset($_SESSION["fotos"]);
    }
    if(isset($_SESSION["usuarios"])){
        unset($_SESSION["usuarios"]);
    }
    if(isset($_SESSION["habitaciones"])){
        unset($_SESSION["habitaciones"]);
    }
}

// Función para asegurarse de que el número tenga un formato decimal adecuado
function convertirADecimal($numero) {
    // Verificar si el número tiene un punto decimal
    if (strpos($numero, '.') === false) {
        // Si no tiene punto decimal, agregar ".0"
        $numero .= '.0';
    }
    // Convertir el número a float y devolverlo
    return $numero;
}

// Funcion auxiliar para comprobar que un archivo es una imagen
function validarFotos() {
    if(!empty($_FILES['fotos']['name'][0])) {
        $datos = array();
        $allowed_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
        foreach($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
            $file_type = exif_imagetype($tmp_name);
            if(!in_array($file_type, $allowed_types)) {
                break;
            } else {
                // Leer la imagen en formato base64
                $imagen_base64 = base64_encode(file_get_contents($tmp_name));
                // Añadir la imagen al array de datos
                $datos[] = $imagen_base64;
            }
        }
        return $datos;
    }
}

// Función que comprueba si una reserva es posible
function comprobarReserva($conexion, $capacidad, $entrada, $salida) {
    // Primero vemos si hay habitaciones con capacidad mayor o igual que pide el usuario
    [$ok, $resultado] = comprobarCapacidad($conexion, $capacidad);    
    if(!$ok) {
        return [false, null];
    }
    // Ahora comprobamos si alguna de esas habitaciones está disponible en el rango de fechas
    $habitacionesDisponibles = array();
    foreach($resultado as $habitacion) {
        if(comprobarDisponibilidad($conexion, $habitacion, $entrada, $salida)) {
            $habitacionesDisponibles[] = $habitacion;
        }
    }
    // Si no hay habitaciones disponibles, devolvemos false
    if(count($habitacionesDisponibles) == 0) {
        return [false, null];
    }
    // Si hay habitaciones disponibles, calculamos la capacidad de cada una
    $capacidades = array();
    foreach($habitacionesDisponibles as $habitacion) {
        $capacidades[$habitacion] = obtenerCapacidad($conexion, $habitacion);
    }
    // Buscar la habitación con la capacidad mínima
    $min = min($capacidades);
    $habitacion = array_search($min, $capacidades);
    return [true, $habitacion];
}

function crearBackup($conexion) {
    // Obtener el listado de tablas en la base de datos
    $result = mysqli_query($conexion, 'SHOW TABLES');
    if (!$result) {
        // Error al obtener las tablas
        return false;
    }

    // Crear una cadena para almacenar el contenido del backup
    $backupContent = '';

    // Recorrer las tablas
    while ($row = mysqli_fetch_row($result)) {
        $table = $row[0];

        // Obtener la estructura de la tabla
        $tableStructure = mysqli_query($conexion, "SHOW CREATE TABLE $table");
        if (!$tableStructure) {
            // Error al obtener la estructura de la tabla
            return false;
        }

        $row = mysqli_fetch_row($tableStructure);

        // Añadir la estructura de la tabla a la cadena de contenido del backup
        $backupContent .= "DROP TABLE IF EXISTS `$table`;\n";
        $backupContent .= $row[1] . ";\n";

        // Obtener los datos de la tabla y añadirlos a la cadena de contenido del backup
        $tableData = mysqli_query($conexion, "SELECT * FROM $table");
        if (!$tableData) {
            // Error al obtener los datos de la tabla
            return false;
        }

        while ($rowData = mysqli_fetch_row($tableData)) {
            $rowData = array_map('addslashes', $rowData);
            $backupContent .= "INSERT INTO `$table` VALUES ('" . implode("', '", $rowData) . "');\n";
        }

        $backupContent .= "\n";
    }

    // Descargar el backup como un archivo
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="backup.sql"');
    echo $backupContent;

    exit();
}


function reiniciarBD($conexion) {
    // Obtener todas las tablas en la base de datos
    $result = $conexion->query("SHOW TABLES");
    if ($result) {
        while ($row = $result->fetch_array()) {
            $table = $row[0];
            // Verificar si la tabla es 'usuariosHotel'
            if ($table == 'usuariosHotel') {
                // Eliminar el contenido de todas las tuplas excepto la del usuario actual
                $conexion->query("DELETE FROM $table WHERE email <> '" . $_SESSION["email"] . "'");
            } else {
                // Eliminar el contenido de cada tabla
                $conexion->query("TRUNCATE TABLE $table");
            }
        }
        return true;
    } else {
        return false;
    }
}

function restaurarBackup($conexion, $filename){
    // Leer el contenido del archivo de backup
    $backupContent = file_get_contents($filename);
    if ($backupContent === false) {
        return false;
    }

    // Separar el contenido del backup en instrucciones SQL
    $sqlCommands = explode(";\n", $backupContent);

    // Ejecutar las instrucciones SQL del backup
    foreach ($sqlCommands as $sqlCommand) {
        $sqlCommand = trim($sqlCommand);
        if (!empty($sqlCommand)) {
            // Cambiar "" por NULL en las instrucciones SQL para que no de excepciones
            $sqlCommand = str_replace("''", "NULL", $sqlCommand);

            $result = $conexion->query($sqlCommand);
            if (!$result) {
                echo "Error MySQL: " . $conexion->error . "\n";
                return false;
            }
        }
    }

    return true;
}

function getLetraReservas($resultado){
    if($resultado == "Confirmada"){
        return "R";
    } else if($resultado == "Pendiente"){
        return "P";
    } else if($resultado == "Mantenimiento"){
        return "M";
    }
}