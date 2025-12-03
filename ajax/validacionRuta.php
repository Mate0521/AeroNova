<?php
require_once("../modelo/Ruta.php");


$origen = $_POST["origen"];
$destino = $_POST["destino"];
$idRuta = $_POST["idRuta"]; // para excluir esta ruta en edición

$ruta = new Ruta();

$res = $ruta->validarRuta($origen, $destino, $idRuta);

echo $res ? "existe" : "ok";
