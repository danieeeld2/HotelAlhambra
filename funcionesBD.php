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

// Función para comprobar si existe la habitación en la BD

function checkNumeroHabitacion($conexion, $habitacion) {
    $query = <<< EOD
        SELECT * FROM habitacionesHotel WHERE habitacion = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta". $conexion->error;
        return false;
    }
    $stmt->bind_param("s", $habitacion);
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

// Función para insertar datos de una habitación
function insertarHabitacion($conexion, $datos) {
    $query = <<< EOD
        INSERT INTO habitacionesHotel (habitacion, capacidad, precio, descripcion)
        VALUES (?,?,?,?)
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return false;
    }
    // bindeamos los parámetros
    $capacidad = (int)$datos['capacidad'];
    $precio = (double)$datos['precio'];
    $stmt->bind_param("sids", $datos['habitacion'], $capacidad, $precio, $datos['descripcion']);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $stmt->close();
    return true;
}

// Función para obtener todas las habitaciones de la base de datos
function getHabitaciones($conexion) {
    $query = <<< EOD
        SELECT * FROM habitacionesHotel
    EOD;
    $resultado = $conexion->query($query);
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return [false, null];
    }
    return [true, $resultado];
}

// Funcion para obtener una habitacion dado su id
function getHabitacionID($conexion, $id) {
    $query = <<< EOD
        SELECT * FROM habitacionesHotel WHERE id = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $stmt->close();
        $resultado->close();
        return [false, null];
    } else {
        $habitacion = $resultado->fetch_assoc();
        $stmt->close();
        $resultado->close();
        return [true, $habitacion];
    }
}

// Función para borrar una habitación dado su id
function borrarHabitacion($conexion, $id) {
    $query = <<< EOD
        DELETE FROM habitacionesHotel WHERE id = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return false;
    }
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $stmt->close();
    return true;
}

// Funcion para insertar fotos de una habitacion
function insertarFotoHabitacion($conexion, $foto, $numeroHabitacion) { 
    $query = <<< EOD
        INSERT INTO fotosHabitaciones (habitacion, imagen) 
        VALUES (?, ?)
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return false;
    }
    $stmt->bind_param("ss", $numeroHabitacion, $foto);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $stmt->close();
    return true;
}

// Función para borrar las fotos de una habitación
function borrarFotosHabitacion($conexion, $habitacion) {
    $query = <<< EOD
        DELETE FROM fotosHabitaciones WHERE habitacion = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return false;
    }
    $stmt->bind_param("s", $habitacion);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return false;
    }
    $stmt->close();
    return true;
}

// Función para obtener las fotos de una habitación
function getFotosHabitacion($conexion, $habitacion) {
    $query = <<< EOD
        SELECT * FROM fotosHabitaciones WHERE habitacion = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("s", $habitacion);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return [false, null];
    }
    $resultadosConsulta = $stmt->get_result();    
    // Verificar si hay resultados
    if ($resultadosConsulta->num_rows === 0) {
        echo "No se encontraron fotos para la habitación.";
        return [false, null];
    }
    // Obtener todas las filas como un arreglo asociativo
    $fotos = $resultadosConsulta->fetch_all(MYSQLI_ASSOC);
    // Liberar recursos
    $stmt->close();
    return [true, $fotos];
}

?>