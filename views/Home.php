<?php
$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelos();
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">Lista de Vuelos</h2>

            <div class="row g-4">
                <?php foreach ($vuelos as $vuelo): ?>
                <div class="col-md-4">
                    <div class="card bg-dark text-light border border-white h-100">
                        <div class="card-body">

                            <h5 class="card-title text-info fw-bold">
                                Vuelo: <?php echo $vuelo->getAvion()->getMatricula(); ?>
                            </h5>

                            <p class="mb-1"><strong class="text-white">Fecha:</strong> 
                                <?php echo $vuelo->getFecha(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Hora Despegue:</strong> 
                                <?php echo $vuelo->getHoraDespegue(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Hora Llegada:</strong> 
                                <?php echo $vuelo->getHoraLlegada(); ?>
                            </p>

                            <hr class="border-white">

                            <p class="mb-1"><strong class="text-info">Piloto:</strong> 
                                <?php echo $vuelo->getPilotoPrincipal()->getNombre() . ' ' . $vuelo->getPilotoPrincipal()->getApellido(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-info">Copiloto:</strong> 
                                <?php echo $vuelo->getCopiloto()->getNombre() . ' ' . $vuelo->getCopiloto()->getApellido(); ?>
                            </p>

                            <hr class="border-white">

                            <p class="mb-1"><strong class="text-white">Origen:</strong> 
                                <?php echo $vuelo->getRuta()->getOrigen(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Destino:</strong> 
                                <?php echo $vuelo->getRuta()->getDestino(); ?>
                            </p>

                            <p class="mt-3">
                                <span class="badge bg-info text-dark">
                                    <?php echo $vuelo->getEstadoVuelo()->getValor(); ?>
                                </span>
                            </p>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>
