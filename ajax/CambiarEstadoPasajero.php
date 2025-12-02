<?php 
require_once (__DIR__ ."/../modelo/Pasajero.php");

$id = $_POST['id'];
$nuevoEstado = $_POST['estado'];

$pasajero = new Pasajero($id, "", "", "", "", "",
 "", $nuevoEstado);
$pasajero->cambiarEstado();

echo $pasajero->getEstadoCuenta();
?>