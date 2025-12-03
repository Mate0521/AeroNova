<?php
require_once(__DIR__ . '/../modelo/Piloto.php');
require_once(__DIR__ . '/../modelo/Estado.php');

if (session_status() === PHP_SESSION_NONE) session_start();

$filtro = isset($_GET["filtro"]) ? trim($_GET["filtro"]) : "";
$filtro = str_replace("%20", " ", $filtro);

// MODELOS
$pilotoModel = new Piloto();
$pilotos = $pilotoModel->buscar($filtro);

$estadoModel = new Estado();
$estados = $estadoModel->obtenerEstadoPilotos();

// Si no hay resultados
if (!$pilotos || count($pilotos) === 0) {
    echo "<div class='alert alert-warning'>No hay pilotos que coincidan con la búsqueda.</div>";
    exit;
}
?>

<table class="table table-striped table-hover table-bordered text-light bg-dark align-middle">
    <thead class="table-secondary text-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Foto</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pilotos as $p): ?>

        <?php
        $estadoPiloto = $p->getEstadoPiloto();

        if (!is_object($estadoPiloto)) {
            $estadoObj = new Estado($estadoPiloto); 
            $estadoObj->obtenerEstadoPilotos();
            $estadoPiloto = $estadoObj;
        }
        ?>

        <tr id="fila-<?= $p->getId() ?>">
            <td><?= htmlspecialchars($p->getId()) ?></td>
            <td><?= htmlspecialchars($p->getNombre()) ?></td>
            <td><?= htmlspecialchars($p->getApellido()) ?></td>
            <td><?= htmlspecialchars($p->getCorreo()) ?></td>
            <td><?= htmlspecialchars($p->getTelefono()) ?></td>

            <td>
                <?php if (!empty($p->getFoto())): ?>
                    <img src="<?= $p->getFoto() ?>" width="50">
                <?php else: ?>
                    <span class="text-muted">Sin foto</span>
                <?php endif; ?>
            </td>


            <td id="estado-<?= $p->getId() ?>">

                <span class="badge bg-info"><?= $estadoPiloto->getValor() ?></span>

                <div class="mt-2 opciones-estado">
                    <?php 
                    $estadoActual = $estadoPiloto->getIdEstado();

                    if (is_array($estados)):
                        foreach ($estados as $e):
                            if ($e->getIdEstado() != $estadoActual):
                    ?>

                    <button class="btn btn-sm btn-outline-warning cambiarEstadoBtn"
                            data-id="<?= $p->getId() ?>"
                            data-estado="<?= $e->getIdEstado() ?>">
                        <?= $e->getValor() ?>
                    </button>

                    <?php 
                            endif;
                        endforeach;
                    else:
                        echo "<div class='text-danger'>⚠ No se pudieron cargar los estados.</div>";
                    endif;
                    ?>
                </div>

            </td>
        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(".cambiarEstadoBtn").on("click", function() {

    let idPiloto = $(this).data("id");
    let nuevoEstado = $(this).data("estado");

    $.post("ajax/cambiarEstadoPiloto.php", 
        { idPiloto: idPiloto, estado: nuevoEstado },
        function(data) {
            $("#estado-" + idPiloto).html(data);
        }
    );
});
</script>
