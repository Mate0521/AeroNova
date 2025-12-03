<?php

require_once (__DIR__ . "/../modelo/Pasajero.php");


if (!isset($_POST["id"]) || !isset($_POST["cambios"])) {
    echo  "Datos incompletos";
    exit;
}

$id = $_POST["id"];
$cambios = $_POST["cambios"]; // array asociativo

$pasajero = new Pasajero($id);
$pasajero->obtenerPasajeroId(); // cargar datos actuales

$resultado = $pasajero->actualizarCampos($cambios);

echo $resultado;
