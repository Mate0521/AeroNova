<?php
include_once("../modelo/Avion.php");


$q = $_GET["q"] ?? "";

$avion = new Avion();
$resultado = $avion->obtenerSugerenciasModelos($q);

echo json_encode($resultado);

