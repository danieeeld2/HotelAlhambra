<?php 
require_once('credenciales.php');

# Conexión a la base de datos
function conectar_bbdd() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_DATABASE);
    if(!$conexion) {
        die("Error al conectar con la base de datos " . mysqli_connect_error());
    }
    return $conexion;
}

# Desconexión de la base de datos
function desconectar_bbdd($conexion) {
    mysqli_close($conexion);
}

?>