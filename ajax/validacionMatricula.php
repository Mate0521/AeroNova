<?php
include_once("../modelo/Avion.php");

$matricula = $_POST["matricula"] ?? "";

$avion = new Avion($matricula);
$avion->obtenerAvionMatricula();

if ($avion->getModelo() != null) {
    echo "exists";
} else {
    echo "ok";
}
