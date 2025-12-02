<?php
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");
include_once(__DIR__ . "/../modelo/Ticket.php");
include_once(__DIR__ . "/../modelo/Pasajero.php");

$id=$_POST["id"];
$pasajero= new  Pasajero($id);
$pasajero->obtenerPasajeroId();

$ticket=new Ticket(null, null, null,
null, $id);
$tickets=$ticket->obtenerTicketsPasajero();


?>
<div class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tikets de <?= $pasajero->getNombre()." ". $pasajero->getApellido() ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="accordion" id="accordionExample">

        <?php
        $index = 1;
        foreach ($tickets as $t) {

            $idTicket = $t->getIdTicket();
            $origen = $t->getVuelo()->getRuta()->getOrigen()->getNombre();
            $destino = $t->getVuelo()->getRuta()->getDestino()->getNombre();

            $titulo = "(Ticket #$idTicket)  Ruta: $origen → $destino ";

            $collapseId = "collapseTicket_" . $index;
            $headingId = "headingTicket_" . $index;
        ?>
            <div class="accordion-item">

                <h2 class="accordion-header" id="<?= $headingId ?>">
                    <button class="accordion-button <?= $index == 1 ? '' : 'collapsed' ?>"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?= $collapseId ?>"
                            aria-expanded="<?= $index == 1 ? 'true' : 'false' ?>"
                            aria-controls="<?= $collapseId ?>">
                        <?= $titulo ?>
                    </button>
                </h2>

                <div id="<?= $collapseId ?>"
                    class="accordion-collapse collapse <?= $index == 1 ? 'show' : '' ?>"
                    data-bs-parent="#accordionExample">

                    <div class="accordion-body">

                        <strong>ID Ticket:</strong> <?= $idTicket ?><br>
                        <strong>Ruta:</strong> <?= $origen ?> → <?= $destino ?><br>

                        <strong>Etado del Ticket:</strong> 
                        <?php
                            switch($t->getEstadoTicket()->getIdEstado()){

                                case 1: // Reservado
                                    echo "<span class='badge bg-primary px-3 py-2'>Reservado</span>";
                                    break;

                                case 2: // Pagado
                                    echo "<span class='badge bg-success px-3 py-2'>Pagado</span>";
                                    break;

                                case 3: // Cancelado
                                    echo "<span class='badge bg-danger px-3 py-2'>Cancelado</span>";
                                    break;

                                case 4: // Check-In realizado
                                    echo "<span class='badge bg-info text-dark px-3 py-2'>Check-In realizado</span>";
                                    break;

                                default:
                                    echo "<span class='badge bg-dark px-3 py-2'>Desconocido</span>";
                            }
                        ?>
                        <br>
                        

                        <strong>Fecha del vuelo:</strong> <?= $t->getVuelo()->getFecha() ?><br>
                        <strong>Hora salida:</strong> <?= $t->getVuelo()->getHoraDespegue() ?><br>
                        <strong>Hora de llegada:</strong> <?= $t->getVuelo()->getHoraLlegada() ?><br>
                        <strong>Piloto:</strong> <?= $t->getVuelo()->getPilotoPrincipal()->getNombre() ?><br>
                        <strong>Copiloto:</strong> <?= $t->getVuelo()->getCopiloto()->getNombre() ?><br>
                        <strong>Avion:</strong> <?= $t->getVuelo()->getAvion()->getMatricula()." -- ". $t->getVuelo()->getAvion()->getModelo() ?><br>
                        <strong>Estado del Vuelo:</strong>
                        <?php switch($t->getVuelo()->getEstadoVuelo()->getIdEstado()){
                            case 1: // Programado
                                echo "<span class='badge bg-primary px-3 py-2'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 2: // En vuelo
                                echo "<span class='badge bg-success px-3 py-2'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 3: // Aterrizado
                                echo "<span class='badge bg-info px-3 py-2 text-dark'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 4: // Retrasado
                                echo "<span class='badge bg-warning text-dark px-3 py-2'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 5: // Cancelado
                                echo "<span class='badge bg-danger px-3 py-2'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 6: // Solicitado
                                echo "<span class='badge bg-secondary px-3 py-2'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            case 7: // Rechazado por piloto
                                echo "<span class='badge bg-danger px-3 py-2 border border-dark'>".$t->getVuelo()->getEstadoVuelo()->getValor() ."</span>";
                                break;

                            default:
                                echo "<span class='badge bg-dark px-3 py-2'>Desconocido</span>";
                        }
                        ?><br>

                        <strong>Asiento:</strong> <?= $t->getPuesto() ?><br>

                        <?php if ($t->getEstadoTicket()) { ?>
                            <strong>Estado:</strong> <?= $t->getEstadoTicket()->getValor() ?><br>
                        <?php } ?>

                    </div>
                </div>

            </div>

        <?php
            $index++;
        }
        ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>