<?php

$mensaje = "";
$ticket=null;

if(isset($_GET["idT"]) || !empty($_GET["idT"])){


    $ticket=base64_decode($_GET["idT"]);

    $ticket = new Ticket($ticket);
    $ticket->obtenerTicketId();

    $pasajero = $ticket->getPasajero();
    $vuelo = $ticket->getVuelo();

    $mensaje = "";

    if ($ticket->getCheckIn() == 1) {
        $mensaje = "El check-in ya fue realizado.";
    }


    $ahora = new DateTime(); 
    $fechaHoraVuelo = new DateTime($vuelo->getFecha() . ' ' . $vuelo->getHoraDespegue());
    $horasRestantes = ($fechaHoraVuelo->getTimestamp() - $ahora->getTimestamp()) / 3600;
    if ($horasRestantes <= 24 && $horasRestantes > 2) {
        $ticket->cambiarEstadoCheckin();
        $mensaje="Check-In realizado satisfactoriamente, passboarding se ha enviado a su correo";
        $cambio=true;
    }else{
        $mensaje="Check-In fuera de tiempo";
        $ticket = null;
    }

}
?>
<div class="container mt-5">
    <div class="card shadow p-4">

        <h2 class="text-center mb-4">Realizar Check-In</h2>

        <form method="POST" class="mb-4">
            <label class="form-label">Ingrese su ID de Ticket:</label>
            <input type="text" name="idTicket" class="form-control" required>
            <button type="submit" name="buscar" class="btn btn-primary mt-3 w-100">
                Hacer Check-In
            </button>
            <small class="text-secondary">Al hacer Clik si es valido se generara su passboarding</small>
        </form>


        <?php if ($mensaje != ""): ?>
            <div class="alert alert-info text-center"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if ($ticket != null): ?>

            <hr>
            <h4>Datos del Pasajero</h4>
            <p><strong>Nombre:</strong> <?= $pasajero->getNombre() . " " . $pasajero->getApellido() ?></p>
            <p><strong>Correo:</strong> <?= $pasajero->getCorreo() ?></p>

            <h4>Datos del Vuelo</h4>
            <p><strong>Destino:</strong> <?= $vuelo->getRuta()->getDestino()->getNombre() ?></p>
            <p><strong>Fecha:</strong> <?= $vuelo->getFecha() ?></p>
            <p><strong>Hora:</strong> <?= $vuelo->getHoraDespegue() ?></p>
            <p><strong>Puesto:</strong> <?= $ticket->getPuesto() ?></p>


        <?php endif; ?>

    </div>
</div>