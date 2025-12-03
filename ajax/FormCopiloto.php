<?php
require_once(__DIR__ . "/../config/Conexion.php");
require_once(__DIR__ . "/../modelo/Piloto.php");
require_once(__DIR__ . "/../modelo/Vuelo.php");

$idVuelo    = $_GET["idVuelo"] ?? null;
$accion     = $_GET["accion"] ?? null;
$idCopiloto = $_GET["idCopiloto"] ?? null;

if (!$idVuelo) {
    echo "<div class='alert alert-danger'>Vuelo no especificado</div>";
    exit();
}

$piloto = new Piloto();
$vuelo  = new Vuelo();

try {
    // 👉 Paso 1: si no hay acción, mostrar formulario
    if (!$accion) {
        $copilotos = $piloto->obtenerPilotos();

        echo "<form id='formCopiloto$idVuelo' class='d-flex gap-2'>";
        echo "<select name='idCopiloto' class='form-select form-select-sm'>";
        foreach ($copilotos as $c) {
            echo "<option value='{$c->getId()}'>{$c->getNombre()} {$c->getApellido()}</option>";
        }
        echo "</select>";
        echo "<button type='submit' class='btn btn-success btn-sm'>Guardar</button>";
        echo "</form>";

        // Script para enviar selección
        echo "<script>
        $('#formCopiloto$idVuelo').on('submit', function(e) {
            e.preventDefault();
            var idCopiloto = $(this).find('select[name=idCopiloto]').val();
            var url = '/ajax/FormCopiloto.php?idVuelo=$idVuelo&accion=guardar&idCopiloto=' + idCopiloto;
            $('#estado$idVuelo').load(url);
        });
        </script>";
    }

    // 👉 Paso 2: si la acción es guardar, actualizar vuelo
    elseif ($accion === "guardar" && $idCopiloto) {
        $vuelo->asignarCopiloto($idVuelo, $idCopiloto);
        echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-success text-light shadow-sm'>
                <i class='bi bi-check-circle me-1'></i> Copiloto asignado
              </div>";
    }

    else {
        echo "<div class='alert alert-info'>Acción no reconocida</div>";
    }

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
