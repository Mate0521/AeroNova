<?php
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");
include_once(__DIR__ . "/../modelo/Ticket.php");

$ticket = new Ticket();
$tickets = $ticket->obtenerTickets(); // Ajusta al método real si es diferente

$rutas = [];

foreach ($tickets as $t) {

    $origen = $t->getVuelo()->getRuta()->getOrigen()->getNombre();
    $destino = $t->getVuelo()->getRuta()->getDestino()->getNombre();

    $ruta = $origen . " → " . $destino;

    if (!isset($rutas[$ruta])) {
        $rutas[$ruta] = 0;
    }

    $rutas[$ruta]++;
}

// Ordenar de mayor a menor
arsort($rutas);

// Tomar top 5
$top5 = array_slice($rutas, 0, 5, true);

$resultado = [];

foreach ($top5 as $ruta => $cantidad) {
    $resultado[] = [
        "ruta" => $ruta,
        "total" => $cantidad
    ];
}

echo json_encode($resultado);
