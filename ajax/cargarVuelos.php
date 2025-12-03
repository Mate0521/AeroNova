<?php
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");

$pag = isset($_POST["pag"]) ? intval($_POST["pag"]) : 1;
$limit = 10;
$offset = ($pag - 1) * $limit;

$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelos($limit, $offset);
?>

<div class="row">
    <?php foreach ($vuelos as $v): ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm bg-dark text-light border-0 h-100">
                <div class="card-body text-center">
                    <h4 class="card-title text-info fw-bold">
                        <?php echo $v->getRuta()->getOrigen()->getNombre() . " → " . $v->getRuta()->getDestino()->getNombre(); ?>
                    </h4>
                    <p class="card-text mt-3">
                        <strong>Fecha:</strong> <?php echo $v->getFecha(); ?><br>
                        <strong>Hora de Despegue:</strong> <?php echo $v->getHoraDespegue(); ?><br>
                        <strong>Avión:</strong> <?php echo $v->getAvion()->getModelo(); ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <?php if (!isset($_SESSION["id"])): ?>
                        <form action="?pid=<?php echo base64_encode('Login'); ?>" method="POST">
                            <button type="submit" class="btn btn-info w-100" name="reservarVuelo">
                                Reservar
                            </button>
                        </form>
                    <?php else: ?>
                        <?php if ($_SESSION['rol'] == 'pasajero'): ?>
                            <div id="planesVuelo_<?php echo $v->getIdVuelo()?>">
                                <button class="btn btn-info w-100 reservarVuelo" 
                                        id="reservar_<?php echo $v->getIdVuelo() ?>" 
                                        data-id="<?php echo $v->getIdVuelo() ?>">
                                    Reservar
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0" role="alert">
                                Usted es un 
                                <?php echo ($_SESSION["rol"] == "piloto") ? "Piloto" : "Administrador"; ?>, 
                                por ende no puede reservar un vuelo.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
