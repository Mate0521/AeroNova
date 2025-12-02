<?php
include_once "../modelo/Vuelo.php";

$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelosLigh(); 

// Contadores por estado
$programado = 0;
$envuelo = 0;
$aterrizado = 0;
$retrasado = 0;
$cancelado = 0;
$solicitado = 0;
$rechazado = 0;

foreach ($vuelos as $v) {

    $estado = $v->getEstadoVuelo();

    switch ($estado) {
        case 1: $programado++; break;
        case 2: $envuelo++; break;
        case 3: $aterrizado++; break;
        case 4: $retrasado++; break;
        case 5: $cancelado++; break;
        case 6: $solicitado++; break;
        case 7: $rechazado++; break;
    }
}

echo json_encode([
    "programado" => $programado,
    "envuelo" => $envuelo,
    "aterrizado" => $aterrizado,
    "retrasado" => $retrasado,
    "cancelado" => $cancelado,
    "solicitado" => $solicitado,
    "rechazado" => $rechazado
]);
