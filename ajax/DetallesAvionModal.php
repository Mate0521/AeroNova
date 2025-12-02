<?php
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");
include_once(__DIR__ . "/../modelo/Ticket.php");


$matricula = $_POST["matricula"];

$avion = new Avion($matricula);
$historial = $avion->obtenerHistorialVuelos();
?>


<div class="modal fade" id="modalHistorialAvion" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Historial de vuelos — Avión <?= $matricula ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        
        <?php if (empty($historial)) { ?>
            <div class="alert alert-warning">Este avión no tiene historial de vuelos.</div>
        <?php } else { ?>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Vuelo</th>
                        <th>Ruta</th>
                        <th>Fecha</th>
                        <th>Salida</th>
                        <th>Llegada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($historial as $v): ?>

                    <tr>
                        <td><?= $v->getIdVuelo() ?></td>
                        <td><?= $v->getRuta()->getOrigen()->getNombre() ?> → 
                            <?= $v->getRuta()->getDestino()->getNombre() ?>
                        </td>
                        <td><?= $v->getFecha() ?></td>
                        <td><?= $v->getHoraDespegue() ?></td>
                        <td><?= $v->getHoraLlegada() ?></td>
                        <td>
                        <?php
                            switch ($v->getEstadoVuelo()->getIdEstado()) {

                                case 1: echo "<span class='badge bg-primary'>Programado</span>"; break;
                                case 2: echo "<span class='badge bg-success'>En Vuelo</span>"; break;
                                case 3: echo "<span class='badge bg-info text-dark'>Aterrizado</span>"; break;
                                case 4: echo "<span class='badge bg-warning text-dark'>Retrasado</span>"; break;
                                case 5: echo "<span class='badge bg-danger'>Cancelado</span>"; break;
                                case 6: echo "<span class='badge bg-warning text-dark'>Solicitado</span>"; break;
                                case 7: echo "<span class='badge bg-danger'>Rechazado por piloto</span>"; break;
                                default: echo "<span class='badge bg-secondary'>Desconocido</span>";
                            }
                        ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>

        <?php } ?>

      </div>
    </div>
  </div>
</div>

<script>
    var modal = new bootstrap.Modal(document.getElementById("modalHistorialAvion"));
    modal.show();
</script>

