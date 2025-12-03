<?php
require_once("../modelo/Ruta.php");

$idRuta = $_POST["idRuta"];
$cambios = $_POST["cambios"];



$ruta = new Ruta($idRuta);

$result = $ruta->actualizarCampos($cambios); // tú lo implementas

echo $result === "ok" ? "ok" : "error";
