<?php

function proximoJueves($desde = 'now') {
    $date = new DateTime($desde);
    $date->modify('next Thursday');
    return $date->format('Y-m-d');
}

function traducirFecha($fecha) {
    $dias = [
        'Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves',
        'Friday' => 'Viernes', 'Saturday' => 'Sábado'
    ];

    $meses = [
        'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
        'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
        'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
        'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
    ];

    $dia = $dias[date('l', strtotime($fecha))];
    $numero = date('d', strtotime($fecha));
    $mes = $meses[date('F', strtotime($fecha))];

    return "$dia $numero de $mes";
}

function registroHabilitadoManual() {
    $archivo = __DIR__ . '/../config/registro_manual.txt';
    if (!file_exists($archivo)) {
        return false; // registros desactivados por defecto
    }

    $estado = trim(file_get_contents($archivo));
    return $estado === 'true'; // habilita registros
}

function cambiarEstadoRegistroManual($nuevoEstado) {
    $archivo = __DIR__ . '/../config/registro_manual.txt';
    file_put_contents($archivo, $nuevoEstado ? 'true' : 'false');
}