<?php
require_once("../modelo/Ticket.php");
require_once("../modelo/Pasajero.php");

session_name("AERO_SESSION");
session_start();

if($_SERVER['REQUEST_METHOD']== 'POST'){
    $ticket = new Ticket();
    $data = $ticket->obtenerDestinosFrecuentesAll();
}else{
    $id=$_SESSION["id"];
    $pasajero = new Pasajero($id);
    $ticket = new Ticket(null, null, null, null, $pasajero->getId());

    $data = $ticket->obtenerDestinosFrecuentes();
}



echo json_encode($data);
