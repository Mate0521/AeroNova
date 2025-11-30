<?php
session_name("AERO_SESSION");
session_start();

include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");
include_once(__DIR__ . "/../modelo/Ticket.php");

// VALIDAR
if (!isset($_POST["idVuelo"]) || empty($_POST["idVuelo"])) {
    echo "<div class='alert alert-danger'>Error: Vuelo no encontrado.</div>";
    exit;
}

$idVuelo = intval($_POST["idVuelo"]);

if (!isset($_SESSION["id"])) {
    echo "<div class='alert alert-danger'>Debes iniciar sesión.</div>";
    exit;
}

$ticket = new Ticket("", "", "", "", $_SESSION["id"], $idVuelo);
$precioBase = $ticket->calcularPrecioBase();


$_SESSION["v$idVuelo"] = [
    "precio" => $precioBase
];


$precioEconomic = $precioBase;
$precioClassic  = $precioBase * 1.15;
$precioBusiness = $precioBase * 1.40;

function formatCOP($n){
    return "$" . number_format($n, 0, ",", ".");
}
?>

<h4 class="text-center text-white my-4">Elige cómo quieres volar</h4>

<div class="row row-cols-1 row-cols-md-3 g-4 my-4">

    <!-- ECONOMIC -->
    <div class="col">
        <div class="card h-100 border-danger bg-dark text-white">
            <div class="card-body">
                <h5 class="card-title text-danger fw-bold">Economic</h5>
                <ul class="list-unstyled mt-3">
                    <li>🛄 1 artículo personal</li>
                    <li>🎒 1 equipaje de mano (10 kg)</li>
                    <li>❌ Sin equipaje de bodega</li>
                    <li>💺 Asiento asignado aleatorio</li>
                    <li>🍽 Menú disponible</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <form action="?pid=<?= base64_encode("reservarVuelo") ?>" method="post">
                    <input type="hidden" name="idVuelo" value="<?= $idVuelo ?>">
                    <input type="hidden" name="clase" value="eco">

                    <button type="submit" class="fw-bold text-danger">
                        <h5 class="fw-bold text-danger"><?= formatCOP($precioEconomic) ?></h5>
                    </button>
                </form>
                <small class="text-secondary">Precio por pasajero</small>
            </div>
        </div>
    </div>

    <!-- CLASSIC -->
    <div class="col">
        <div class="card h-100 border-primary bg-dark text-white">
            <div class="card-body">
                <h5 class="card-title text-primary fw-bold">Classic</h5>
                <ul class="list-unstyled mt-3">
                    <li>🛄 1 artículo personal</li>
                    <li>🎒 1 equipaje de mano</li>
                    <li>🧳 1 equipaje de bodega</li>
                    <li>💺 Asiento Economy incluido</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <form action="?pid=<?= base64_encode("reservarVuelo") ?>" method="post">
                    <input type="hidden" name="idVuelo" value="<?= $idVuelo ?>">
                    <input type="hidden" name="clase" value="clas">

                    <button type="submit" class="fw-bold text-primary">
                        <h5 class="fw-bold text-primary"><?= formatCOP($precioClassic) ?></h5>
                    </button>
                </form>
                <small class="text-secondary">Precio por pasajero</small>
            </div>
        </div>
    </div>

    <!-- BUSINESS -->
    <div class="col">
        <div class="card h-100 border-warning bg-dark text-white">
            <div class="card-body">
                <h5 class="card-title text-warning fw-bold">Business</h5>
                <ul class="list-unstyled mt-3">
                    <li>🛄 Artículo personal + mano</li>
                    <li>🧳 2 equipajes de bodega</li>
                    <li>💺 Asiento Business</li>
                    <li>🛫 Check-in prioritario</li>
                    <li>🏆 Sala VIP</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <form action="?pid=<?= base64_encode("reservarVuelo") ?>" method="post">
                    <input type="hidden" name="idVuelo" value="<?= $idVuelo ?>">
                    <input type="hidden" name="clase" value="bus">

                    <button type="submit" class="fw-bold text-warning">
                        <h5 class="fw-bold text-warning"><?= formatCOP($precioBusiness) ?></h5>
                    </button>
                </form>
                <small class="text-secondary">Precio por pasajero</small>
            </div>
        </div>
    </div>

</div>
