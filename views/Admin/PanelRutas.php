<?php
require_once("modelo/Ruta.php");
require_once("modelo/Ciudad.php");

$ruta = new Ruta();
$rutas = $ruta->consultar();

$ciudad = new Ciudad();
$ciudades = $ciudad->obtenerCiudades();

if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=" . base64_encode("Error"));
    exit;
}
?>

<div class="container mt-5">
    <div class="card-header">
        <h3><i class="bi bi-signpost-split-fill"></i>  Gestión de Rutas</h3>
    </div>
    <div class="d-flex justify-content-center mt-4 mb-4">
        <div class="col-6">
            <input type="text" id="filtro" class="form-control">
        </div>
    </div>
    <div class="card shadow">

        <div class="card-body">

            <?php if (count($rutas) == 0): ?>
                <div class="alert alert-warning">No hay rutas registradas.</div>
            <?php else: ?>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Duración</th>
                        <th>Distancia (KM)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="t_body_rutas">

                <?php foreach ($rutas as $r): ?>
                    <tr data-row="<?= $r->getIdRuta() ?>">

                        <td><?= $r->getIdRuta() ?></td>

                        <!-- ORIGEN -->
                        <td class="edit-campo-select"
                            data-id="<?= $r->getIdRuta() ?>"
                            data-campo="origen"
                            data-valor="<?= $r->getOrigen()->getId() ?>">
                            <?= $r->getOrigen()->getNombre() ?>
                        </td>

                        <!-- DESTINO -->
                        <td class="edit-campo-select"
                            data-id="<?= $r->getIdRuta() ?>"
                            data-campo="destino"
                            data-valor="<?= $r->getDestino()->getId() ?>">
                            <?= $r->getDestino()->getNombre() ?>
                        </td>

                        <!-- DURACIÓN -->
                        <td class="edit-campo"
                            data-id="<?= $r->getIdRuta() ?>"
                            data-campo="duracion_estimada"
                            data-valor="<?= $r->getDuracionEstimada() ?>">
                            <?= $r->getDuracionEstimada() ?>
                        </td>

                        <!-- DISTANCIA -->
                        <td class="edit-campo"
                            data-id="<?= $r->getIdRuta() ?>"
                            data-campo="distancia_KM"
                            data-valor="<?= $r->getDistanciaKM() ?>">
                            <?= $r->getDistanciaKM() ?>
                        </td>

                        <!-- BOTONES -->
                        <td id="acciones_<?= $r->getIdRuta() ?>">
                            <button class="btn btn-secondary btn-sm btn-info-ruta"
                                    data-id="<?= $r->getIdRuta() ?>">
                                <i class="bi bi-map"></i> Info
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

<div id="modal_ruta">

</div>



<script>
    const CIUDADES = [
        <?php foreach ($ciudades as $c): ?>
            { id: "<?= $c['idCiudad'] ?>", nombre: "<?= $c['nombre'] ?>" },
        <?php endforeach; ?>
    ];



    $(document).ready(function() {

        $(document).on("click", ".edit-campo", function () {

            let td = $(this);
            if (td.hasClass("editing")) return;

            td.addClass("editing");

            let valor = td.data("valor");

            td.html(`
                <input type="text" class="form-control campo-input"
                    value="${valor}">
            `);

            activarBotonGuardar(td.data("id"));
        });


        $(document).on("click", ".edit-campo-select", function () {

            let td = $(this);
            if (td.hasClass("editing")) return;

            td.addClass("editing");

            let selected = td.data("valor");

            let select = `<select class="form-select campo-select">`;

            CIUDADES.forEach(c => {
                select += `
                    <option value="${c.id}" ${c.id == selected ? "selected" : ""}>
                        ${c.nombre}
                    </option>
                `;
            });

            select += `</select>`;

            td.html(select);

            activarBotonGuardar(td.data("id"));
        });

        function activarBotonGuardar(id) {

            $("#acciones_" + id).html(`
                <button class="btn btn-success btn-sm btn-guardar-cambios"
                        data-id="${id}">
                    <i class="bi bi-save"></i> Guardar
                </button>
            `);
        }

        $(document).on("click", ".btn-guardar-cambios", function () {

            let idRuta = $(this).data("id");
            let cambios = {};

            // Recorremos los campos editados solamente
            $(`tr[data-row="${idRuta}"] td`).each(function () {

                let td = $(this);

                if (td.hasClass("editing")) {

                    let campo = td.data("campo");

                    let nuevoValor;

                    if (td.hasClass("edit-campo-select")) {
                        nuevoValor = td.find("select").val();
                    } else {
                        nuevoValor = td.find("input").val().trim();
                    }

                    cambios[campo] = nuevoValor;
                }

            });

            if (cambios["origen"] !== undefined || cambios["destino"] !== undefined) {

                let origen = cambios["origen"] ?? $(`tr[data-row="${idRuta}"] td[data-campo="origen"]`).data("valor");
                let destino = cambios["destino"] ?? $(`tr[data-row="${idRuta}"] td[data-campo="destino"]`).data("valor");

                if (origen == destino) {
                    alert("El origen y destino no pueden ser iguales.");
                    return;
                }

                $.ajax({
                    url: "ajax/validacionRuta.php",
                    type: "POST",
                    data: {
                        origen: origen,
                        destino: destino,
                        idRuta: idRuta
                    },
                    success: function(res) {

                        if (res === "existe") {
                            alert("Ya existe una ruta con ese ORIGEN y DESTINO.");
                            return;
                        }

                        guardarCambiosRuta(idRuta, cambios);
                    },
                    error: function() {
                        alert("Error validando la ruta en el servidor");
                    }
                });

            } else {

                guardarCambiosRuta(idRuta, cambios);
            }

        });

        function guardarCambiosRuta(idRuta, cambios) {

            $.ajax({
                url: "ajax/ActualizarRuta.php",
                type: "POST",
                data: {
                    idRuta: idRuta,
                    cambios: cambios
                },
                success: function(res) {

                    if (res === "ok") {

                        // Restauramos valores en la tabla
                        for (let c in cambios) {

                            let td = $(`tr[data-row="${idRuta}"] td[data-campo="${c}"]`);

                            let nuevo = cambios[c];

                            if (td.hasClass("edit-campo-select")) {
                                let ciudad = CIUDADES.find(x => x.id == nuevo);
                                td.text(ciudad.nombre);
                            } else {
                                td.text(nuevo);
                            }

                            td.data("valor", nuevo);
                            td.removeClass("editing");
                        }

                        $("#acciones_" + idRuta).html(`
                            <button class="btn btn-secondary btn-sm btn-info-ruta" data-id="${idRuta}">
                                <i class="bi bi-map"></i> Info
                            </button>
                        `);

                        alert("Cambios guardados correctamente.");
                    } else {
                        alert("Error al guardar cambios");
                    }
                }
            });

        }

    });

    $("#filtro").on("keyup", function() {

        let texto = $(this).val().trim();

        $.ajax({
            url: "ajax/BuscarRuta.php",
            type: "GET",
            data: { q: texto },
            success: function(res) {
                $("#t_body_rutas").html(res);
            }
        });
    });

    $(document).on("click", ".btn-info-ruta", function () {

        let idRuta = $(this).data("id");

        $.ajax({
            url: "ajax/InfoRuta.php",
            type: "GET",
            data: { idRuta: idRuta },
            success: function (html) {
                $("#modal_ruta").html(html);

                $("#modalDetalleRuta").modal("show");

            }
        });

    });

</script>

