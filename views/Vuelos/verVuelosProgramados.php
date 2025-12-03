<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . "/../../config/Conexion.php");
require_once(__DIR__ . "/../../modelo/Vuelo.php");

// Validar rol
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    echo "<p class='text-danger'>Acceso denegado</p>";
    exit();
}

$vueloModel = new Vuelo();
$vuelos = $vueloModel->obtenerVuelosProgramados();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vuelos Programados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
<div class="container mt-5">
    <h2 class="mb-4">Listado de Vuelos Programados (Estado 1)</h2>

    <div id="mensajeAjax"></div>

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>ID Vuelo</th>
                <th>Fecha</th>
                <th>Hora Despegue</th>
                <th>Hora Llegada</th>
                <th>Piloto</th>
                <th>Copiloto</th>
                <th>Avión</th>
                <th>Ruta</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($vuelos as $v): ?>
            <tr>
                <td><?= $v->getIdVuelo() ?></td>
                <td><?= $v->getFecha() ?></td>
                <td><?= $v->getHoraDespegue() ?></td>
                <td><?= $v->getHoraLlegada() ?></td>
                <td>
                    <?php 
                        $piloto = $v->getPilotoPrincipal();
                        echo $piloto ? $piloto->getNombre()." ".$piloto->getApellido() : "Sin asignar";
                    ?>
                </td>
                <td>
                    <?php 
                        $copiloto = $v->getCopiloto();
                        echo $copiloto ? $copiloto->getNombre()." ".$copiloto->getApellido() : "Sin asignar";
                    ?>
                </td>
                <td>
                    <?php 
                        $avion = $v->getAvion();
                        echo $avion ? $avion->getModelo()." (".$avion->getMatricula().")" : "Sin avión";
                    ?>
                </td>
                <td>
                    <?php 
                        $ruta = $v->getRuta();
                        echo $ruta ? $ruta->getOrigen()->getNombre()." → ".$ruta->getDestino()->getNombre() : "Sin ruta";
                    ?>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm solicitarCopiloto" data-id="<?= $v->getIdVuelo() ?>">
                        Solicitar Copiloto
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on("click", ".solicitarCopiloto", function() {
    const idVuelo = $(this).data("id");

    $.ajax({
        url: "ajax/solicitarCopiloto.php",
        method: "POST",
        data: { idVuelo },
        dataType: "json",
        success: function(response) {
            const tipo = response.status;
            const html = `
                <div class="alert alert-${tipo} alert-dismissible fade show mt-3" role="alert">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            $("#mensajeAjax").html(html);
        },
        error: function() {
            $("#mensajeAjax").html(`
                <div class="alert alert-danger mt-3">Error al procesar la solicitud.</div>
            `);
        }
    });
});
</script>
</body>
</html>
