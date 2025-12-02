<?php
include_once "../modelo/Vuelo.php";

$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelosLigh(); 


$meses = array_fill(1, 12, 0);

foreach ($vuelos as $v) {

    $fecha = $v->getFecha(); 

    if ($fecha) {
        $mes = (int) date("m", strtotime($fecha));
        $meses[$mes]++;
    }
}

echo json_encode($meses);
