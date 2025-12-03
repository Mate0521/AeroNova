<?php
require_once("../modelo/Estado.php");
require_once("../modelo/Ruta.php");
require_once("../modelo/Vuelo.php");


$idRuta = $_GET["idRuta"];

$v = new Vuelo();
$v->setRuta($idRuta);
$vuelos = $v->consultarPorRuta();

?>

<div class="modal fade" id="modalDetalleRuta" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Información de Ruta #<?= $idRuta ?></h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h5>Vuelos asociados</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Avion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($vuelos as $vuelo): ?>
                        <tr>
                            <td><?= $vuelo->getIdVuelo() ?></td>
                            <td><?= $vuelo->getFecha() ?></td>
                            <td><?= $vuelo->getAvion()->getModelo() ?></td>
                            <td>
                            <?php
                            echo "<span class='badge bg-primary'>".$v->getEstadoVuelo()."</span>";
                                switch ($v->getEstadoVuelo()) {

                                    case "1": echo "<span class='badge bg-primary'>Programado</span>"; break;
                                    case "2": echo "<span class='badge bg-success'>En Vuelo</span>"; break;
                                    case "3": echo "<span class='badge bg-info text-dark'>Aterrizado</span>"; break;
                                    case "4": echo "<span class='badge bg-warning text-dark'>Retrasado</span>"; break;
                                    case "5": echo "<span class='badge bg-danger'>Cancelado</span>"; break;
                                    case "6": echo "<span class='badge bg-warning text-dark'>Solicitado</span>"; break;
                                    case "7": echo "<span class='badge bg-danger'>Rechazado por piloto</span>"; break;
                                    default: echo "<span class='badge bg-secondary'>Desconocido</span>";
                                }
                                
                            ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <hr>

            </div>

        </div>
    </div>
</div>
