<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../modelo/Vuelo.php');

if (session_status() === PHP_SESSION_NONE) session_start();

$idVuelo    = $_GET["idVuelo"] ?? null;
$accion     = $_GET["accion"] ?? null; // aceptar o rechazar
$idCopiloto = $_SESSION["id"] ?? null;

if (!$idVuelo || !$accion) {
    echo "<div class='alert alert-danger'>Datos incompletos</div>";
    exit();
}

$vuelo = new Vuelo();

try {
    if ($accion === "aceptar") {
        $vuelo->aceptarCopiloto($idVuelo);
        echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-success text-light shadow-sm'>
                <i class='bi bi-check-circle me-1'></i> Programado
              </div>";
    } elseif ($accion === "rechazar") {
        $vuelo->rechazarCopiloto($idVuelo);
        echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-warning text-dark shadow-sm'>
                <i class='bi bi-arrow-repeat me-1'></i> Pendiente por copiloto
              </div>";
    } else {
        echo "<div class='alert alert-info'>Acción no reconocida</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
