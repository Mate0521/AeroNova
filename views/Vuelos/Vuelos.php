<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . "/../../config/Conexion.php");
require_once(__DIR__ . "/../../modelo/Vuelo.php");

$vueloModel = new Vuelo();
$vuelos = $vueloModel->obtenerVuelosProgramados();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vuelos Disponibles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">✈️ Vuelos Disponibles</h2>

    <div id="mensajeAjax"></div>

    <div class="row">
        <?php foreach ($vuelos as $v): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <h4 class="card-title text-primary fw-bold">
                            <?php 
                                $ruta = $v->getRuta();
                                echo $ruta ? $ruta->getOrigen()->getNombre()." → ".$ruta->getDestino()->getNombre() : "Sin ruta";
                            ?>
                        </h4>
                        <p class="card-text mt-3">
                            <strong>Fecha:</strong> <?= $v->getFecha() ?><br>
                            <strong>Hora:</strong> <?= $v->getHoraDespegue() ?> → <?= $v->getHoraLlegada() ?>
                        </p>
                        <a href="?pid=<?= base64_encode("Login")?>" class="btn btn-success btn-lg w-100">
                            Reservar
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
