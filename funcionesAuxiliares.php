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
?>