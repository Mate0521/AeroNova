<?php
$avion = new Avion();
$aviones = $avion->consultar(); 


if ($_SESSION["rol"] != "admin") {
    header('Location: ?pid=' . base64_encode("Error"));
}
?>
<div>
    <div class="container">
        <div class="row mt-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Gestión de Aviones</h3>
                    </div>

                    <div id="modal_avion"></div>

                    <div class="card-body">
                    <?php if (count($aviones) == 0): ?>
                        <div class='alert alert-warning' role='alert'>No hay aviones registrados</div>
                    <?php else: ?>

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Modelo</th>
                                    <th>Capacidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($aviones as $a): ?>
                            <tr data-row="<?= $a->getMatricula() ?>">

                                <!-- Matrícula NO editable -->
                                <td><strong><?= $a->getMatricula() ?></strong></td>

                                <!-- Modelo editable -->
                                <td class="edit-campo"
                                    data-id="<?= $a->getMatricula() ?>"
                                    data-campo="modelo"
                                    data-valor="<?= $a->getModelo() ?>">
                                    <?= $a->getModelo() ?>
                                </td>

                                <!-- Capacidad editable -->
                                <td class="edit-campo"
                                    data-id="<?= $a->getMatricula() ?>"
                                    data-campo="capacidad"
                                    data-valor="<?= $a->getCapacidad() ?>">
                                    <?= $a->getCapacidad() ?>
                                </td>

                                <!-- Acciones -->
                                <td id="acciones_<?= $a->getMatricula() ?>">
                                    <button class="btn btn-primary btn-sm ver_detalles"
                                        data-id="<?= $a->getMatricula() ?>">
                                        <i class="bi bi-eye"></i> Historial
                                    </button>
                                </td>

                            </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>

                    <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>

    // ------- EDITAR CAMPOS -------
    $(document).on("click", ".edit-campo", function(){

        let td = $(this);

        if (td.hasClass("editing")) return;

        td.addClass("editing");
        let valor = td.data("valor");

        td.html(`
            <input type="text" class="form-control campo-input" value="${valor}">
        `);

        let idRow = td.data("id");
        $("#acciones_" + idRow).html(`
            <button class="btn btn-success btn-sm btn-guardar-cambios" data-id="${idRow}">
                <i class="bi bi-floppy-fill"></i> Guardar
            </button>
        `);
    });

    $(document).on("click", ".btn-guardar-cambios", function () {

        let id = $(this).data("id");
        let cambios = {};

        $(`tr[data-row="${id}"] .edit-campo`).each(function () {

            let td = $(this);

            if (td.hasClass("editing")) {
                let campo = td.data("campo");
                let nuevoValor = td.find(".campo-input").val();
                cambios[campo] = nuevoValor;
            }
        });

        $.ajax({
            url: "ajax/ActualizarDatosAvion.php",
            type: "POST",
            data: { matricula: id, cambios: cambios },
            success: function(res) {

                if (res == "ok") {

                    $(`tr[data-row="${id}"] .edit-campo`).each(function () {
                        let td = $(this);
                        let campo = td.data("campo");

                        if (cambios[campo] !== undefined) {
                            td.text(cambios[campo]);
                            td.data("valor", cambios[campo]);
                        }

                        td.removeClass("editing");
                    });

                    $("#acciones_" + id).html(`
                        <button class="btn btn-primary btn-sm ver_detalles"
                            data-id="${id}">
                            <i class="bi bi-eye"></i> Detalles
                        </button>
                    `);

                    alert("Cambios guardados correctamente.");
                }
            },
            error: function() {
                alert("Error en la comunicación con el servidor");
            }
        });

    });

    $(document).on("click", ".ver_detalles", function() {

        let id = $(this).data("id");

        $.ajax({
            url: "ajax/DetallesAvionModal.php",
            type: "POST",
            data: { matricula: id },
            success: function(respuesta) {

                $("#modal_avion").html(respuesta);
                $(".modal").modal("show");
            },
            error: function() {
                alert("Error cargando detalles del avión.");
            }
        });
    });

</script>
