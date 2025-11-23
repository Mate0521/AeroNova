<?php
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");

$filtro = $_POST["filtro"];
$filtro = str_replace("%20", " ", $filtro);
$vuelo = new Vuelo();
$vuelos = $vuelo->buscarVueloDestino($filtro);

if (count($vuelos) == 0) {
    echo "<div class='alert alert-warning' role='alert'>
            No hay vuelos disponibles
        </div>";
    return;
}

foreach ($vuelos as $vuelo) :
    $ticket =new Ticket("", "", "", "",$_SESSION['id'], $vuelo->getIdVuelo());
    $precio = $ticket->calcularPrecioBase();
    $_SESSION["idVuelo"]["precio"]=$precio
?>
<div class="row mb-3"> 
    <div class="card mb-3 bg-dark text-light border border-white w-100" >
    <div class="row g-0">
        <div class="col-md-4">
        <img src="..." class="img-fluid rounded-start" alt="...">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title text-info fw-bold"><?php echo $vuelo->getRuta()->getOrigen() . " - " . $vuelo->getRuta()->getDestino(); ?></h5>
                <p class="card-text">Fecha: <?php echo $vuelo->getFecha(); ?></p>
                <p class="card-text">Hora de Despegue: <?php echo $vuelo->getHoraDespegue(); ?></p>
                <p class="card-text">Avion: <?php echo $vuelo->getAvion()->getModelo(); ?></p>
            </div>
        </div>
        <div id="planesVuelo">
            <div>
                <h3><span>$<?php echo $precio ?></span></h3>
            </div>
            <div class="card-footer" data-id="<?php $vuelo->getIdVuelo() ?>">
                <p class="btn btn-info w-100  vuelo">Reservar</p>
            </div>
        </div>
    </div>
    </div>
</div>
<?php endforeach ?>
