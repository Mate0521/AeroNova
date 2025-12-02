<?php
include_once "../modelo/Pasajero.php";

$pasajero = new Pasajero();
$lista = $pasajero->consultar();

$activos = 0;
$inactivos = 0;

foreach ($lista as $p) {
    if ($p->getEstadoCuenta() == 1) {
        $activos++;
    } else {
        $inactivos++;
    }
}

echo json_encode([
    "activos" => $activos,
    "inactivos" => $inactivos
]);
