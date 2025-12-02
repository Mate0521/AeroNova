<?php
$pasajero = new Pasajero();
$pasajeros = $pasajero->consultar();

$id = $_SESSION["id"];
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
                        <h3>Consultar Pasajeros</h3>
                    </div>
                    <div id="modal_tickets">
                        
                    </div>
                    <div class="card-body">
                        <?php if (count($pasajeros) == 0): ?>
                            <div class='alert alert-warning' role='alert'>No hay registros</div>
                        <?php else: ?>
                        
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($pasajeros as $c): ?>
                            <tr data-row="<?= $c->getId() ?>">

                                <td class="edit-campo" 
                                    data-id="<?= $c->getId() ?>" 
                                    data-campo="nombre" 
                                    data-valor="<?= $c->getNombre() ?>">
                                    <?= $c->getNombre() ?>
                                </td>

                                <td class="edit-campo" 
                                    data-id="<?= $c->getId() ?>" 
                                    data-campo="apellido" 
                                    data-valor="<?= $c->getApellido() ?>">
                                    <?= $c->getApellido() ?>
                                </td>

                                <td class="edit-campo" 
                                    data-id="<?= $c->getId() ?>" 
                                    data-campo="correo" 
                                    data-valor="<?= $c->getCorreo() ?>">
                                    <?= $c->getCorreo() ?>
                                </td>

                                <td class="edit-campo" 
                                    data-id="<?= $c->getId() ?>" 
                                    data-campo="telefono" 
                                    data-valor="<?= $c->getTelefono() ?>">
                                    <?= $c->getTelefono() ?>
                                </td>

                                <td>
                                    <div id="estado_<?= $c->getId() ?>" 
                                         data-id="<?= $c->getId() ?>" 
                                         data-estado="<?= $c->getEstadoCuenta() ?>" 
                                         class="estado-toggle">

                                        <?php if ($c->getEstadoCuenta() == 1): ?>
                                            <span><i class="bi bi-check-circle text-success"></i> Activo</span>
                                        <?php else: ?>
                                            <span><i class="bi bi-x-circle text-danger"></i> Inactivo</span>
                                        <?php endif; ?>

                                    </div>
                                </td>


                                <td class="acciones" id="acciones_<?= $c->getId() ?>">
                                    <button class="btn btn-success btn-sm" id="ver_t_<?= $c->getId() ?>" data-id="<?= $c->getId() ?>">
                                        <i class="bi bi-ticket-perforated"></i> Ver Tickets
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
    $(document).on("click", ".edit-campo", function(){

        let td = $(this);
        
        if (td.hasClass("editing")) return;

        td.addClass("editing");

        let valor = td.data("valor");

        td.html(`
            <input type="text" class="form-control campo-input" value="${valor}">
        `);

        // Mostrar el botón Guardar al final de la fila
        let idRow = td.data("id");
        $("#acciones_" + idRow).html(`
            <button class="btn btn-success btn-sm btn-guardar-cambios" data-id="${idRow}">
                <i class="bi bi-floppy-fill"></i> Guardar cambios
            </button>
        `);

    });

    $(document).on("click", ".btn-guardar-cambios", function () {

        let id = $(this).data("id");
        let cambios = {}; //solo editado

        $(`tr[data-row="${id}"] .edit-campo`).each(function () {

            let td = $(this);

            if (td.hasClass("editing")) { // Solo los que fueron editados

                let campo = td.data("campo");
                let nuevoValor = td.find(".campo-input").val();

                cambios[campo] = nuevoValor; // Guardamos key: value
            }

        });

        $.ajax({
            url: "ajax/ActualizacionDatos.php",
            type: "POST",
            data: {
                id: id,
                cambios: cambios
            },
            success: function (res) {
                try {
                    
                    if (res == "ok") {

                        // Restaurar los valores en la vista
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
                            <button class="btn btn-success btn-sm" id="ver_t_${id}" data-id="${id}">
                                <i class="bi bi-ticket-perforated"></i> Ver Tickets
                            </button>
                        `);
                        alert(" Cambios guardados correctamente.");
                    }

                } catch (e) {
                    alert("Error inesperado: " + res + e);
                }
            },
            error: function () {
                alert("❌ Error en la comunicación con el servidor");
            }
        });

    });

    $(document).on("click", '[id^="estado_"]', function() {
        let div = $(this);
        let id = div.data("id");
        let estado = div.data("estado");

        let nuevoEstado = estado == 1 ? 0 : 1;

        $.ajax({
            url: "ajax/CambiarEstadoPasajero.php",
            type: "POST",
            data: { id: id, estado: nuevoEstado },
            success: function(respuesta) {

                if (respuesta == 1) {
                    div.html("<span><i class='bi bi-check-circle text-success'></i> Activo</span>");
                } else {
                    div.html("<span><i class='bi bi-x-circle text-danger'></i> Inactivo</span>");
                }

                div.data("estado", respuesta);
            }
        });
    });

    $(document).on("click", "[id^='ver_t_']", function() {

        let id = $(this).data("id");

        $.ajax({
            url: "ajax/MostrarTickets.php",
            type: "POST",
            data: { id: id},
            success: function(respuesta) {

                $("#modal_tickets").html(respuesta); 

                $(".modal").modal("show"); 
            },
            error: function() {
                alert("Error cargando los tickets");
            }
        });
    });


</script>
