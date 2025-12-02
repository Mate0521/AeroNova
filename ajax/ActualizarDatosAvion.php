<?php
include_once("../modelo/Avion.php");


if (!isset($_POST["matricula"]) || !isset($_POST["cambios"])) {
    echo "error";
    exit;
}

$avion = new Avion($_POST["matricula"]);
$cambios = $_POST["cambios"];

$respuesta = $avion->actualizarCampos($cambios);

echo $respuesta;
