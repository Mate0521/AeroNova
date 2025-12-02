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
$tickets = $ticket->obtenerTickets(); 

$reservado = 0;
$pagado = 0;
$cancelado = 0;
$checkin = 0;

foreach ($tickets as $t) {
    $estado = $t->getEstadoTicket();

    switch($estado) {
        case 1: $reservado++; break;
        case 2: $pagado++; break;
        case 3: $cancelado++; break;
        case 4: $checkin++; break;
    }
}

echo json_encode([
    "reservado" => $reservado,
    "pagado" => $pagado,
    "cancelado" => $cancelado,
    "checkin" => $checkin
]);
