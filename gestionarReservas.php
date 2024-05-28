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
    // Primero vemos si hay habitaciones con cpacidad mayor o igual que pide el usuario
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
?>