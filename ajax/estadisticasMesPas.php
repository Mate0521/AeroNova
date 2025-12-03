<?php
require_once("../modelo/Ticket.php");
require_once("../modelo/Pasajero.php");

session_name("AERO_SESSION");
session_start();

$pasajero = new Pasajero($_SESSION["id"]);
$ticket = new Ticket(null, null, null, null, $pasajero->getId());

$data = $ticket->obtenerVuelosPorMes();


echo json_encode($data);
