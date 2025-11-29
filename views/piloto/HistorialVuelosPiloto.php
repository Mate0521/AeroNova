<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("modelo/Vuelo.php");
require_once("modelo/Piloto.php");

$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelos();

$id = $_SESSION["id"] ?? null;

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "piloto") {
    header('Location: ?pid=' . base64_encode("noAutorizado.php"));
    exit();
}

$piloto = new Piloto($id);
$piloto->obtenerPilotoId();
?>

<body>
<div class="container">
    <div class="row mt-5">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Buscar vuelos</h3>
                </div>

                <div class="card-body">

                    <!-- BUSCADOR -->
                    <div class="row mb-3">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <input type="text" id="filtro" class="form-control"
                                   placeholder="Buscar por copiloto, avión o ruta...">
                        </div>
                    </div>

                    <!-- RESULTADOS AJAX -->
                    <div id="resultados"></div>

                    <!-- TABLA COMPLETA -->
                    <div id="tablaGeneral" class="table-responsive mt-4">
                        <table class="table table-striped table-hover table-bordered text-light bg-dark align-middle">
                            <thead class="table-secondary text-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Despegue</th>
                                    <th>Llegada</th>
                                    <th>Piloto</th>
                                    <th>Copiloto</th>
                                    <th>Avión</th>
                                    <th>Ruta</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($vuelos as $v): 
                                $pil = $v->getPilotoPrincipal();
                                $copo = $v->getCopiloto();
                                $avion = $v->getAvion();
                                $ruta = $v->getRuta();
                                $estado = $v->getEstadoVuelo();
                            ?>
                                <tr>
                                    <td><?= $v->getIdVuelo() ?></td>
                                    <td><?= $v->getFecha() ?></td>
                                    <td><?= $v->getHoraDespegue() ?></td>
                                    <td><?= $v->getHoraLlegada() ?></td>
                                    <td><?= $pil->getNombre() ?></td>
                                    <td><?= $copo->getNombre() ?></td>
                                    <td><?= $avion->getModelo() ?> (<?= $avion->getMatricula() ?> – Cap: <?= $avion->getCapacidad() ?>)</td>
                                    <td><?= $ruta->getOrigen()->getNombre() ?> → <?= $ruta->getDestino()->getNombre() ?></td>
                                    <td><?= $estado->getValor() ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$("#filtro").on("keyup", function() {

    let texto = $(this).val().trim();

    if (texto.length >= 2) {

        $("#tablaGeneral").hide();   // ocultar tabla completa
        $("#resultados").html("<p class='text-center text-light'>Buscando...</p>");

        let filtro = encodeURIComponent(texto);
        let url = "ajax/buscarvuelosajax.php?filtro=" + filtro;

        $("#resultados").load(url);

    } else {
        $("#resultados").html("");  
        $("#tablaGeneral").show();  // mostrar tabla original
    }
});
</script>
