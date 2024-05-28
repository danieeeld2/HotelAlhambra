<?php

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
?>