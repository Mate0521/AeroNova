<?php
require_once (__DIR__ . "/../modelo/Vuelo.php");

$idVuelo = $_GET["idVuelo"];
$estado = $_GET["estado"];

$vuelo = new Vuelo();
$vuelo->CambiarEstado($idVuelo,$estado);

// Iconos según estado
if ($estado == 1) {
    echo "<span class='badge bg-success'>Programado</span>";
} else if ($estado == 7) {
    echo "<span class='badge bg-danger'>Rechazado</span>";
} else {
    echo "<span class='badge bg-secondary'>Estado $estado</span>";
}
