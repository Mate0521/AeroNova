<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Clases modelo
require_once(__DIR__ . "/../../config/Conexion.php");
require_once(__DIR__ . "/../../modelo/Piloto.php");
require_once(__DIR__ . "/../../modelo/Avion.php");
require_once(__DIR__ . "/../../modelo/Ruta.php");
require_once(__DIR__ . "/../../modelo/Estado.php");
require_once(__DIR__ . "/../../modelo/Vuelo.php");

// Validar rol
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    echo "<p class='text-danger'>Acceso denegado</p>";
    exit();
}

$pilotoModel = new Piloto();
$avionModel  = new Avion();
$rutaModel   = new Ruta();

// Obtener datos
$pilotos = $pilotoModel->obtenerPilotos();
$aviones = $avionModel->obtenerAviones();
$rutas   = $rutaModel->obtenerRutas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Vuelos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-light">
<div class="container mt-5">
    <h2 class="mb-4">Solicitar vuelos a pilotos</h2>

    <!-- Contenedor para mensajes AJAX -->
    <div id="mensajeAjax"></div>

    <form id="formSolicitarVuelos">
        <div id="contenedorVuelos">
            <div class="card mb-3 bg-secondary text-light vuelo-item">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Piloto -->
                        <div class="col-md-3">
                            <label class="form-label">Piloto</label>
                            <select name="pilotos[]" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($pilotos as $p): ?>
                                    <option value="<?= $p->getId() ?>">
                                        <?= $p->getNombre() ?> <?= $p->getApellido() ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Avión -->
                        <div class="col-md-3">
                            <label class="form-label">Avión</label>
                            <select name="aviones[]" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($aviones as $a): ?>
                                    <option value="<?= $a->getMatricula() ?>">
                                        <?= $a->getModelo() ?> (<?= $a->getMatricula() ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Ruta -->
                        <div class="col-md-3">
                            <label class="form-label">Ruta</label>
                            <select name="rutas[]" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach ($rutas as $r): ?>
                                    <option value="<?= $r->getIdRuta() ?>">
                                        <?= $r->getOrigen() ? $r->getOrigen()->getNombre() : "Desconocido" ?>
                                        →
                                        <?= $r->getDestino() ? $r->getDestino()->getNombre() : "Desconocido" ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fechas[]" class="form-control" required>
                        </div>

                        <!-- Hora de despegue -->
                        <div class="col-md-3">
                            <label class="form-label">Hora Despegue</label>
                            <input type="time" name="horasDespegue[]" class="form-control" required>
                        </div>

                        <!-- Botón eliminar -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm eliminarVuelo">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /#contenedorVuelos -->

        <div class="d-flex justify-content-between">
            <button type="button" id="agregarVuelo" class="btn btn-outline-light mb-3">
                <i class="bi bi-plus-circle"></i> Agregar otro vuelo
            </button>

            <button type="submit" class="btn btn-primary mb-3">
                <i class="bi bi-send"></i> Solicitar vuelos
            </button>
        </div>
    </form>
</div><!-- /.container -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Agregar nuevo vuelo
$(document).on("click", "#agregarVuelo", function() {
    let nuevo = $(".vuelo-item").first().clone();
    nuevo.find("input").val("");
    nuevo.find("select").val("");
    $("#contenedorVuelos").append(nuevo);
});

// Eliminar vuelo
$(document).on("click", ".eliminarVuelo", function() {
    if ($(".vuelo-item").length > 1) {
        $(this).closest(".vuelo-item").remove();
    }
});

// Enviar formulario por AJAX
$(document).on("submit", "#formSolicitarVuelos", function(e) {
    e.preventDefault();

    $.ajax({
        url: "ajax/crearVuelo.php",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            let tipo = response.status;
            let html = `
                <div class="alert alert-${tipo} alert-dismissible fade show mt-3" role="alert">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            `;
            $("#mensajeAjax").html(html);

            // Opcional: limpiar formulario si fue exitoso
            if (tipo === "success") {
                $("#formSolicitarVuelos")[0].reset();
            }
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
