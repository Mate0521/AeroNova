<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once("../modelo/Piloto.php");
require_once("../modelo/Estado.php");
require_once("../config/Conexion.php");

header("Content-Type: text/html; charset=UTF-8");

if (!isset($_POST["idPiloto"], $_POST["estado"])) {
    echo "<div class='text-danger'>Error: datos incompletos</div>";
    exit;
}

$idPiloto = intval($_POST["idPiloto"]);
$idEstado = intval($_POST["estado"]);

if ($idPiloto <= 0 || $idEstado <= 0) {
    echo "<div class='text-danger'>Error: ID inválido</div>";
    exit;
}

// Cambiar estado en la BD
$piloto = new Piloto();
$actualizado = $piloto->actualizarEstado($idPiloto, $idEstado);

// Si no se actualizó, verificar si el piloto existe
if (!$actualizado) {
    $pilotoVerificacion = new Piloto($idPiloto);
    $pilotoVerificacion->obtenerPilotoId();

    if (!is_object($pilotoVerificacion->getEstadoPiloto())) {
        echo "<div class='text-danger'>Error: piloto no encontrado</div>";
        exit;
    }

    // Si el estado ya era el mismo, no mostrar error
    if ($pilotoVerificacion->getEstadoPiloto()->getIdEstado() == $idEstado) {
        // continuar sin error
    } else {
        echo "<div class='text-danger'>Error al actualizar el estado</div>";
        exit;
    }
}

// Obtener datos actualizados del piloto
$pilotoActualizado = new Piloto($idPiloto);
$pilotoActualizado->obtenerPilotoId();

$estadoObj = $pilotoActualizado->getEstadoPiloto();
if (!is_object($estadoObj)) {
    echo "<div class='text-danger'>Error: estado no disponible</div>";
    exit;
}

$estadoActual = $estadoObj->getIdEstado();
$estadoTexto = $estadoObj->getValor();

// Cargar lista de estados
$estadoModel = new Estado();
$estados = $estadoModel->obtenerEstadoPilotos();
?>

<span class="badge bg-info"><?= $estadoTexto ?></span>

<div class="mt-2 opciones-estado">
<?php foreach ($estados as $e): ?>
    <?php if ($e->getIdEstado() != $estadoActual): ?>
        <button class="btn btn-sm btn-outline-warning cambiarEstadoBtn"
                data-id="<?= $idPiloto ?>"
                data-estado="<?= $e->getIdEstado() ?>">
            <?= $e->getValor() ?>
        </button>
    <?php endif; ?>
<?php endforeach; ?>
</div>

<script>
// volver a enganchar los eventos del AJAX recién generado
$(".cambiarEstadoBtn").on("click", function() {
    let idPiloto = $(this).data("id");
    let nuevoEstado = $(this).data("estado");

    $.post("ajax/cambiarEstadoPiloto.php", { idPiloto: idPiloto, estado: nuevoEstado }, function(data) {
        $("#estado-" + idPiloto).html(data);
    });
});
</script>
