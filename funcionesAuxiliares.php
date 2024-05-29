<?php 
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
?>