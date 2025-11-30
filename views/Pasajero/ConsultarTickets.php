<?php

$tickets= new Ticket(null,null,null,null,$_SESSION["id"]);
$tickets = $tickets->obtenerTicketsPasajero();

?>

<div class="container mt-4">

    <h2 class="mb-4">Mis Tickets</h2>

    <table class="table table-dark table-striped align-middle">
        <thead>
            <tr>
                <th>ID Ticket</th>
                <th>Estado</th>
                <th>Ruta</th>
                <th>Salida</th>
                <th>Puesto</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($tickets as $t): 
                $vuelo = $t->getVuelo();
                $ruta  = $vuelo->getRuta();
            ?>

                <tr>
                    <td><?= $t->getIdTicket() ?></td>

                    <td><?= $t->getEstadoTicket()->getValor() ?></td>

                    <td>
                        <?= $ruta->getOrigen()->getNombre() ?> 
                        → 
                        <?= $ruta->getDestino()->getNombre() ?>
                    </td>

                    <td>
                        <?= $vuelo->getFecha() ?> <br>
                        <small><?= $vuelo->getHoraDespegue() ?></small>
                    </td>

                    <td><?= $t->getPuesto() ?></td>

                    <td>$<?= number_format($t->getPrecio(), 0, ',', '.') ?></td>

                    <td>


                        <a href="tickets/ticket_<?= $t->getIdTicket() ?>.pdf"
                           class="btn btn-success btn-sm mb-1">
                           Voleto
                        </a>

                        <?php if ($t->getEstadoTicket()->getIdEstado()==2) { ?>
                            <a href="?pid=<?= base64_encode("Checkin")?>&idT=<?= base64_encode($t->getIdTicket()) ?>"
                               class="btn btn-warning btn-sm">
                               Hacer Check-in
                            </a>
                        <?php } ?>

                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>
</div>
