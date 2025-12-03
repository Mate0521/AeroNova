<?php
require_once("modelo/Ciudad.php");
require_once("modelo/Ruta.php");
require_once("dao/RutaDAO.php");

// Obtener ciudades para los SELECT
$ciudad = new Ciudad();
$ciudades = $ciudad->obtenerCiudades();

// Procesar formulario
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $ruta = new Ruta(
        null,
        $_POST["duracion"],
        $_POST["distancia"],
        $_POST["origen"],
        $_POST["destino"]
    );

    $resultado = $ruta->crearRuta();

    if ($resultado == "ok") {
        $mensaje = "<div class='alert alert-success'>Ruta creada correctamente</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al crear ruta</div>";
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-7 mx-auto">
            <div class="card-header">
                <h3> <i class="bi bi-signpost-split"></i> Crear Nueva Ruta</h3>
            </div>
            <div class="card shadow">

                <div class="card-body">

                    <?= $mensaje ?>

                    <form method="POST" id="form_ruta">

                        <!-- ORIGEN -->
                        <label class="mt-2">Ciudad Origen</label>
                        <select name="origen" id="origen" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($ciudades as $c): ?>
                                <option value="<?= $c['idCiudad'] ?>">
                                    <?= $c['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- DESTINO -->
                        <label class="mt-3">Ciudad Destino</label>
                        <select name="destino" id="destino" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($ciudades as $c): ?>
                                <option value="<?= $c['idCiudad'] ?>">
                                    <?= $c['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- VALIDACIÓN VISUAL -->
                        <div id="ruta_feedback" class="mt-2"></div>

                        <!-- DURACIÓN -->
                        <label class="mt-3">Duración Estimada (HH:MM:SS)</label>
                        <input type="time" step="1" name="duracion" class="form-control" required>

                        <!-- DISTANCIA -->
                        <label class="mt-3">Distancia (KM)</label>
                        <input type="number" name="distancia" class="form-control" required>

                        <button type="submit" id="btn_guardar" class="btn btn-primary mt-4 w-100" disabled>
                            Crear Ruta
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>


<script>
$(document).ready(function() {

    function validarRuta() {
        let origen = $("#origen").val();
        let destino = $("#destino").val();

        // evitar origen = destino
        if (origen !== "" && destino !== "" && origen === destino) {
            $("#ruta_feedback").html(
                "<span class='text-danger'>Origen y destino no pueden ser iguales</span>"
            );
            $("#btn_guardar").prop("disabled", true);
            return;
        }

        // cuando no hay datos suficientes
        if (origen === "" || destino === "") {
            $("#ruta_feedback").html("");
            $("#btn_guardar").prop("disabled", true);
            return;
        }

        // AJAX de validación
        $.ajax({
            url: "ajax/validacionRuta.php",
            type: "POST",
            data: {
                origen: origen,
                destino: destino
            },
            success: function(res) {
                if (res === "existe") {
                    $("#ruta_feedback").html(
                        "<span class='text-danger'>Esta ruta ya existe</span>"
                    );
                    $("#btn_guardar").prop("disabled", true);

                } else {
                    $("#ruta_feedback").html(
                        "<span class='text-success'>✔ Ruta disponible</span>"
                    );
                    $("#btn_guardar").prop("disabled", false);
                }
            }
        });

    }

    $("#origen, #destino").on("change", validarRuta);

});
</script>
