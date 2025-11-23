<?php

$idVuelo = $_POST["idVuelo"];
$precioBase = $_SESSION[$idVuelo]["precio"] ?? 0;


$precioEconomic = $precioBase;
$precioClassic  = $precioBase * 1.15;
$precioBusiness = $precioBase * 1.40;

function formatCOP($num){
    return "$" . number_format($num, 0, ",", ".");
}
?>

<h4 class="text-center text-white my-4">Elige cómo quieres volar</h4>

<div class="row row-cols-1 row-cols-md-3 g-4">

    <!-- ECONOMIC -->
    <div class="col">
        <div class="card h-100 border-danger bg-dark text-white">
            <div class="card-body">
                <h5 class="card-title text-danger fw-bold">Economic</h5>
                <ul class="list-unstyled mt-3">
                    <li>🛄 1 artículo personal (bolso)</li>
                    <li>🎒 1 equipaje de mano (10 kg)</li>
                    <li>❌ Sin equipaje de bodega</li>
                    <li>💺 Asiento asignado aleatoriamente</li>
                    <li>🍽 Menú a bordo disponible a la venta</li>
                    <li>🔁 Sin reembolsos / sin cambios</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="?pid=<?php echo base64_encode("reservarVuelo") ?>&idV=<?php echo $idVuelo?>"><h5 class="fw-bold text-danger"><?= formatCOP($precioEconomic) ?></h5></a>
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
                    <li>🎒 1 equipaje de mano (10 kg)</li>
                    <li>🧳 1 equipaje de bodega (23 kg)</li>
                    <li>💺 Asiento Economy incluido</li>
                    <li>🍽 Menú a bordo incluido</li>
                    <li>🔁 Cambios (antes del vuelo)</li>
                    <li>⛔ No hay reembolsos</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="?pid=<?php echo base64_encode("reservarVuelo") ?>&idV=<?php echo $idVuelo?>"><h5 class="fw-bold text-primary"><?= formatCOP($precioClassic) ?></h5></a>
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
                    <li>🛄 1 artículo personal</li>
                    <li>🎒 1 equipaje de mano (10 kg)</li>
                    <li>🧳 2 equipajes de bodega (32 kg)</li>
                    <li>💺 Asiento Business Class</li>
                    <li>🛫 Check-in y embarque prioritario</li>
                    <li>🍽 Menú de alta cocina incluido</li>
                    <li>🏆 Acceso a sala VIP</li>
                    <li>🔁 Cambios gratis</li>
                    <li>💸 Reembolsos disponibles</li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="?pid=<?php echo base64_encode("reservarVuelo") ?>&idV=<?php echo $idVuelo?>"><h5 class="fw-bold text-warning"><?= formatCOP($precioBusiness) ?></h5></a>
                <small class="text-secondary">Precio por pasajero</small>
            </div>
        </div>
    </div>

</div>
