<?php
session_name("AERO_SESSION");
session_start();

include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Avion.php");
include_once(__DIR__ . "/../modelo/Piloto.php");
include_once(__DIR__ . "/../modelo/Ciudad.php");
include_once(__DIR__ . "/../modelo/Ruta.php");
include_once(__DIR__ . "/../modelo/Estado.php");
include_once(__DIR__ . "/../modelo/Vuelo.php");
include_once(__DIR__ . "/../modelo/Ticket.php");

$filtro = $_POST["filtro"];
$filtro = str_replace("%20", " ", $filtro);

$pag = isset($_POST["pag"]) ? intval($_POST["pag"]) : 1;
$limit = 10;
$offset = ($pag - 1) * $limit;

$vuelo = new Vuelo();
$vuelos = $vuelo->buscarVueloDestino($filtro, $limit, $offset);

if ($vuelos instanceof PDOException) {
    echo "<div class='alert alert-danger'>Error en la consulta: $vuelos </div>";
    exit;
}

if (count($vuelos)==0) {
    echo "<div class='alert alert-warning'>No se encontraron vuelos</div>";
    exit;
}

foreach ($vuelos as $vuelo) :
    
?>
<div class="row mb-3"> 
    <div class="card mb-3 bg-dark text-light border border-white w-100" >
    <div class="row g-0">
        <div class="col-md-4">
        <img src="..." class="img-fluid rounded-start" alt="...">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title text-info fw-bold"><?php echo $vuelo->getRuta()->getOrigen()->getNombre() . " - " . $vuelo->getRuta()->getDestino()->getNombre(); ?></h5>
                <p class="card-text">Fecha: <?php echo $vuelo->getFecha(); ?></p>
                <p class="card-text">Hora de Despegue: <?php echo $vuelo->getHoraDespegue(); ?></p>
                <p class="card-text">Avion: <?php echo $vuelo->getAvion()->getModelo(); ?></p>
            </div>
        </div>
        <div id="planesVuelo_<?php echo $vuelo->getIdVuelo()?>">
            <div class="card-footer" >
                <p class="btn btn-info w-100" id="reservar_<?php echo $vuelo->getIdVuelo() ?>" data-id="<?php echo $vuelo->getIdVuelo() ?>">Reservar</p>
            </div>
        </div>
    </div>
    </div>
</div>
<?php endforeach ?>
