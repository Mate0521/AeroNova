<?php

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $matricula = $_POST["matricula"];
    $modelo = $_POST["modelo"];
    $capacidad = $_POST["capacidad"];

    $avion = new Avion($matricula, $modelo, $capacidad);

    $resultado = $avion->crearAvion();

    if ($resultado === "ok") {
        $mensaje = "<div class='alert alert-success'>Avión creado correctamente</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al crear avión: $resultado</div>";
    }
}

?>

<div class="container mt-4">
    <h2><i class="bi bi-airplane-engines"></i> Registrar Nuevo Avión</h2>

    <?= $mensaje ?>

    <div class="card shadow p-4 mt-3">
        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Matrícula</label>
                <input type="text" class="form-control" id="matricula" name="matricula" required>

                <div id="matricula_feedback" class="mt-1"></div>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" autocomplete="off"
                       id="modelo" name="modelo" required>

                <div id="suggestions_modelo"
                     class="list-group position-absolute w-100 mt-1"
                     style="z-index:999;"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Capacidad</label>
                <input type="number" class="form-control" name="capacidad" min="1" required>
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Registrar Avión
            </button>

        </form>
    </div>
</div>

<script>
$(document).ready(function() {

    $("#matricula").on("keyup", function() {
        let m = $(this).val().trim();

        if (m.length < 3) return;

        $.post("ajax/validacionMatricula.php", { matricula: m }, function(res) {

            if (res === "exists") {
                $("#matricula_feedback").html(
                    "<span class='text-danger'><i class='bi bi-x-circle'></i> Matrícula ya registrada</span>"
                );
            } else {
                $("#matricula_feedback").html(
                    "<span class='text-success'><i class='bi bi-check-circle'></i> Disponible</span>"
                );
            }
        });
    });


    $("#modelo").on("keyup", function() {

        let txt = $(this).val().trim();

        if (txt.length < 2) {
            $("#suggestions_modelo").html("");
            return;
        }

        $.get("ajax/sujerenciaModelos.php", { q: txt }, function(res) {

            let datos = JSON.parse(res);
            let html = "";

            datos.forEach(m => {
                html += `<button type='button' class='list-group-item list-group-item-action sug'>${m}</button>`;
            });

            $("#suggestions_modelo").html(html);
        });
    });

    $(document).on("click", ".sug", function() {
        $("#modelo").val($(this).text());
        $("#suggestions_modelo").html("");
    });

});
</script>
