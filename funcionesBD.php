<?php 
// Funcion para insertar un nuevo usuario en la base de datos
function insertarUsuario($conexion, $datos) {
    $query = <<< EOD
        INSERT INTO usuariosHotel (nombre, apellidos, dni, email, clave, tarjeta) 
        VALUES (?, ?, ?, ?, ?, ?)
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return false;
    }
    // Encriptamos la contraseña
    $datos["clave"] = password_hash($datos['clave'], PASSWORD_DEFAULT);
    $stmt->bind_param("ssssss", $datos["nombre"], $datos["apellidos"], $datos["dni"], $datos["email"], $datos["clave"], $datos["tarjeta"]);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $stmt->close();
    return true;
}

// Funcion para comprobar si existe el email en la base de datos
function checkEmail($conexion, $email){
    $query = <<< EOD
        SELECT * FROM usuariosHotel WHERE email = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta". $conexion->error;
        return false;
    }
    $stmt->bind_param("s", $email);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return false;
    } else {
        $resultado->close();
        $stmt->close();
        return true;
    }
}

// Funcion para consultar si un email ya existe en la base de datos
function getUsuario($conexion, $email, $claveIntroducida) {
    $query = <<< EOD
        SELECT * FROM usuariosHotel WHERE email = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta". $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $datos = $stmt->get_result();
    if($datos->num_rows == 0) {
        $stmt->close();
        $datos->close();
        return [false, null];
    } else {
        $resultado = $datos->fetch_assoc();
        if(password_verify($claveIntroducida, $resultado["clave"])) {
            $stmt->close();
            $datos->close();
            return [true, $resultado];
        } else {
            $stmt->close();
            $datos->close();
            return [false, null];
        }
    }
}

?>