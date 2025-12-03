<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once("modelo/Piloto.php");
require_once("modelo/Estado.php");

$pilotoModel = new Piloto();
$pilotos = $pilotoModel->obtenerPilotos(); 

$estado = new Estado();
$estados = $estado->obtenerEstadoPilotos(); // ← esto debe devolver array de objetos Estado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Pilotos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">

<div class="container mt-5">
    <div class="card bg-secondary text-light">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Buscar Pilotos</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPiloto">
                + Agregar Piloto
            </button>
        </div>

        <div class="card-body">

            <!-- Input de búsqueda -->
            <div class="row mb-3">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <input type="text" id="filtro" class="form-control"
                           placeholder="Buscar por nombre, correo o estado...">
                </div>
            </div>

            <!-- Resultados AJAX -->
            <div id="resultados"></div>

            <!-- Tabla general -->
            <div id="tablaGeneral" class="table-responsive mt-4">
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
                        <tr id="fila-<?= $p->getId() ?>">
                            <td><?= $p->getId() ?></td>
                            <td><?= $p->getNombre() ?></td>
                            <td><?= $p->getApellido() ?></td>
                            <td><?= $p->getCorreo() ?></td>
                            <td><?= $p->getTelefono() ?></td>

                            <td>
                                <?php if(!empty($p->getFoto())): ?>
                                    <img src="<?= $p->getFoto() ?>" width="50">
                                <?php else: ?>
                                    <span class="text-muted">Sin foto</span>
                                <?php endif; ?>
                            </td>

                            <td id="estado-<?= $p->getId() ?>">
                                <span class="badge bg-info"><?= $p->getEstadoPiloto()->getValor() ?></span>
                                <div class="mt-2 opciones-estado">
                                    <?php 
                                    $estadoActual = $p->getEstadoPiloto()->getIdEstado();
                                    if (is_array($estados) || is_object($estados)):
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
            </div>

        </div>
    </div>
</div>

<!-- Modal Agregar Piloto -->
<div class="modal fade" id="modalAgregarPiloto" tabindex="-1" aria-labelledby="modalAgregarPilotoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarPilotoLabel">Agregar Piloto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- enctype necesario para subir archivos -->
        <form id="formAgregarPiloto" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="id" class="form-label">ID Piloto</label>
            <input type="number" class="form-control" id="id" name="id" required>
          </div>
          <div class="row mb-3">
            <div class="col">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="col">
              <label for="apellido" class="form-label">Apellido</label>
              <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
              <label for="correo" class="form-label">Correo</label>
              <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="col">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <?php foreach ($estados as $e): ?>
                <option value="<?= $e->getIdEstado() ?>"><?= $e->getValor() ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {

    // Filtro en tiempo real
    $("#filtro").on("keyup", function() {
        let texto = $(this).val().trim();

        if (texto.length >= 2) {
            $("#tablaGeneral").hide();
            $("#resultados").html("<p class='text-center'>Buscando...</p>");

            $.get("ajax/buscarpilotosajax.php", { filtro: texto }, function(data) {
                $("#resultados").html(data);
            });

        } else {
            $("#resultados").html("");
            $("#tablaGeneral").show();
        }
    });

    // Cambio de estado
    $(".cambiarEstadoBtn").on("click", function() {
        let idPiloto = $(this).data("id");
        let nuevoEstado = $(this).data("estado");

        $.post("ajax/cambiarEstadoPiloto.php", { idPiloto: idPiloto, estado: nuevoEstado }, function(data) {
            $("#estado-" + idPiloto).html(data);
        });
    });

$("#formAgregarPiloto").on("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "ajax/agregarPiloto.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json", // ← esto es clave
        success: function(response) {
            if (response.status === "success") {
                alert(response.message);
                $("#modalAgregarPiloto").modal("hide");
                $("#tablaGeneral").load(location.href + " #tablaGeneral>*", "");
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            alert("❌ Error de conexión con el servidor");
            console.error("AJAX error:", status, error);
        }
    });
});

});
</script>

</body>
</html>
