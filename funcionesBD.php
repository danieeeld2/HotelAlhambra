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

// Funcion oara obtener todos los usuarios de la base de datos
function getUsuarios($conexion) {
    $query = <<< EOD
        SELECT * FROM usuariosHotel
    EOD;
    $resultado = $conexion->query($query);
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return [false, null];
    }
    return [true, $resultado];
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
        return [false, null];
    }
    // Obtener todas las filas como un arreglo asociativo
    $fotos = $resultadosConsulta->fetch_all(MYSQLI_ASSOC);
    // Liberar recursos
    $stmt->close();
    return [true, $fotos];
}

function borrarFotoID($conexion, $id){
    $query = <<< EOD
        DELETE FROM fotosHabitaciones WHERE id = ?
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

function contarReservas($conexion){
    $query = <<< EOD
        SELECT COUNT(*) as count FROM reservasHotel
    EOD;
    $resultado = $conexion->query($query);
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return [false, null];
    }
    $resultado = $resultado->fetch_assoc();
    return [true, $resultado];
}

function contarReservasUsuario($conexion, $email){
    $query = <<< EOD
        SELECT COUNT(*) as count FROM reservasHotel WHERE email = ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $stmt->close();
        $resultado->close();
        return [false, null];
    } else {
        $resultado = $resultado->fetch_assoc();
        $stmt->close();
        return [true, $resultado];
    }
}

function getReservas($conexion, $offset, $limit){
    $query = <<< EOD
        SELECT * FROM reservasHotel LIMIT ? , ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $stmt->close();
        $resultado->close();
        return [false, null];
    } else {
        $reservas = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $resultado->close();
        return [true, $reservas];
    }
}

function getReservasUsuario($conexion, $email, $offset, $limit){
    $query = <<< EOD
        SELECT * FROM reservasHotel WHERE email = ? LIMIT ? , ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta" . $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("sii", $email, $offset, $limit);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $stmt->close();
        $resultado->close();
        return [false, null];
    } else {
        $reservas = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $resultado->close();
        return [true, $reservas];
    }
}

// Función que devuelve el precio de una habitación
function obtenerPrecio($conexion, $habitacion) {
    $query = <<< EOD
        SELECT precio FROM habitacionesHotel WHERE habitacion = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $habitacion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return -1;
    } else {
        $precio = $resultado->fetch_assoc()["precio"];
        $resultado->close();
        $stmt->close();
        return $precio;
    }
}

// Función para crear la tupla de reserva en la base de datos con estado "pendiente"
function crearReservaPendiente($conexion, $habitacion, $email, $datos){
    $query = <<< EOD
        INSERT INTO reservasHotel (habitacion, personas, entrada, salida, comentario, precio, estado, marca, email) VALUES (?, ?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP(), ?)
    EOD;
    $stmt = $conexion->prepare($query);
    $precio = obtenerPrecio($conexion, $habitacion);
    $estado = "Pendiente";
    $stmt->bind_param("sisssdss", $habitacion, $datos["numeropersonas"], $datos["entrada"], $datos["salida"], $datos["comentario"], $precio, $estado, $email);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;
}

//Función para borrar las tuplas de reservas con estado "pendiente" que han caducado
// Consideramos que ha caducado cuando han pasado 30 segundos desde que se creó
function borrarReservasCaducadas($conexion) {
    $query = <<< EOD
        DELETE FROM reservasHotel WHERE estado = "pendiente" AND (UNIX_TIMESTAMP() - marca) > 30
    EOD;
    $stmt = $conexion->prepare($query);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;
}

// Función que devuelve el identificador de una reserva
function obtenerIdReserva($conexion, $habitacion, $email, $datos) {
    $query = <<< EOD
        SELECT id FROM reservasHotel WHERE habitacion = ? AND email = ? AND personas = ? AND entrada = ? AND salida = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssiss", $habitacion, $email, $datos["numeropersonas"], $datos["entrada"], $datos["salida"]);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return -1;
    } else {
        $id = $resultado->fetch_assoc()["id"];
        $resultado->close();
        $stmt->close();
        return $id;
    }
}

// Función que devuelve los datos de una reserva dado su identificador
function obtenerDatosReserva($conexion, $id) {
    $query = <<< EOD
        SELECT * FROM reservasHotel WHERE id = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return null;
    } else {
        $datos = $resultado->fetch_assoc();
        $resultado->close();
        $stmt->close();
        return $datos;
    }
}

// Función para borrar una reserva dado su id
function borrarReserva($conexion, $id) {
    $query = <<< EOD
        DELETE FROM reservasHotel WHERE id = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;
}

// Función para confirmar una reserva
function confirmarReserva($conexion, $id) {
    $query = <<< EOD
        UPDATE reservasHotel SET estado = "Confirmada" WHERE id = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;
}

function establecerHabitacionReforma($conexion, $habitacion, $fecha1, $fecha2) {
    $query = <<< EOD
        INSERT INTO reservasHotel (habitacion, entrada, salida, estado, marca) VALUES (?, ?, ?, "Mantenimiento", UNIX_TIMESTAMP())
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sss", $habitacion, $fecha1, $fecha2);
    $resultado = $stmt->execute();
    $stmt->close();
    return $resultado;

}

// Función que devuelve la lista de habitaciones con capacidad mayor o igual a la solicitada (devuelve un array con las habitaciones)
function comprobarCapacidad($conexion, $capacidad){
    $query = <<< EOD
        SELECT * FROM habitacionesHotel WHERE capacidad >= ?
    EOD;
    $stmt = $conexion->prepare($query);
    if(!$stmt) {
        echo "Error al preparar la consulta". $conexion->error;
        return [false, null];
    }
    $stmt->bind_param("i", $capacidad);
    $resultado = $stmt->execute();
    if(!$resultado) {
        echo "Error al ejecutar la consulta". $conexion->error;
        return [false, null];
    }
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return [false, null];
    } else {
        // Devolver la lista de los nombres de habitaciones (son únicos en el sistema)
        $habitaciones = array();
        while($habitacion = $resultado->fetch_assoc()) {
            $habitaciones[] = $habitacion["Habitacion"];
        }
        $resultado->close();
        $stmt->close();
        return [true, $habitaciones];
    }
}


// Función que comprueba si una habitación está disponible en un rango de fechas
function comprobarDisponibilidad($conexion, $habitacion, $fecha1, $fecha2){
    $count = -1;
    $query = <<<EOD
        SELECT COUNT(*) as count FROM reservasHotel WHERE habitacion = ? AND ((entrada BETWEEN ? AND ?) OR (salida BETWEEN ? AND ?))
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssss", $habitacion, $fecha1, $fecha2, $fecha1, $fecha2);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Si hay alguna reserva en ese rango de fechas, la habitación no está disponible
    return $count == 0;
}
// Función que, dada una habitación, devuelve su capacidad
function obtenerCapacidad($conexion, $habitacion) {
    $query = <<< EOD
        SELECT capacidad FROM habitacionesHotel WHERE habitacion = ?
    EOD;
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $habitacion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if($resultado->num_rows == 0) {
        $resultado->close();
        $stmt->close();
        return -1;
    } else {
        $capacidad = $resultado->fetch_assoc()["capacidad"];
        $resultado->close();
        $stmt->close();
        return $capacidad;
    }
}


?>