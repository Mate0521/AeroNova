<?php 
require_once(__DIR__ . "/../../config/Conexion.php");
require_once(__DIR__ . "/../../modelo/Vuelo.php");

if (session_status() === PHP_SESSION_NONE) session_start();

// ✅ ADMIN: ya NO usamos $_SESSION["id"]
$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelosPendienteporcopiloto(); // <-- tu método ya existe


?>
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered text-light bg-dark align-middle">
    <thead class="table-secondary text-dark">
        <tr>
            <th>ID</th><th>Fecha</th><th>Despegue</th><th>Llegada</th>
            <th>Piloto</th><th>Copiloto</th><th>Avión</th><th>Ruta</th><th>Acciones / Estado</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($vuelos as $v): ?>
    <tr>
        <td><?= $v->getIdVuelo() ?></td>
        <td><?= $v->getFecha() ?></td>
        <td><?= $v->getHoraDespegue() ?></td>
        <td><?= $v->getHoraLlegada() ?></td>
        <td><?= $v->getPilotoPrincipal()->getNombre() ?></td>
        <td><?= $v->getCopiloto() ? $v->getCopiloto()->getNombre() : "Sin asignar" ?></td>
        <td><?= $v->getAvion()->getModelo() ?> (<?= $v->getAvion()->getMatricula() ?>)</td>
        <td><?= $v->getRuta()->getOrigen()->getNombre() ?> → <?= $v->getRuta()->getDestino()->getNombre() ?></td>
        <td>
            <div id="estado<?= $v->getIdVuelo() ?>">
            <?php if ($v->getEstadoVuelo()->getIdEstado() == 8): ?>
                <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill bg-warning text-dark shadow-sm">
                    <i class="bi bi-person-plus me-1"></i> Pendiente por copiloto
                </div>
                <div class="d-flex gap-1 mt-1">
                    <button id="solicitar<?= $v->getIdVuelo() ?>" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-check-circle"></i> Seleccionar copiloto
                    </button>
                </div>
            <?php else: ?>
                <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill bg-info text-dark shadow-sm">
                    <i class="bi bi-info-circle me-1"></i> <?= $v->getEstadoVuelo()->getValor() ?>
                </div>
            <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
</div>

<script>
<?php foreach ($vuelos as $v): 
    if ($v->getEstadoVuelo()->getIdEstado() == 8): ?>
    
    $('#solicitar<?= $v->getIdVuelo() ?>').on('click', function() {
        // ✅ Ruta AJAX CORRECTA
        var url = '../../ajax/FormCopiloto.php?idVuelo=<?= $v->getIdVuelo() ?>';

        $('#estado<?= $v->getIdVuelo() ?>').load(url);
    });

<?php endif; endforeach; ?>
</script>
