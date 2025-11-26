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
    <div class="col-12">
        <div class="row">
            <?php foreach ($vuelos as $v): ?>
            <div class="row mb-3"> 
                <div class="card mb-3 bg-dark text-light border border-white w-100" >
                <div class="row g-0">
                    <div class="col-md-4">
                    <img src="..." class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title text-info fw-bold"><?php echo $v->getRuta()->getOrigen()->getNombre() . " - " . $v->getRuta()->getDestino()->getNombre(); ?></h5>
                            <p class="card-text">Fecha: <?php echo $v->getFecha(); ?></p>
                            <p class="card-text">Hora de Despegue: <?php echo $v->getHoraDespegue(); ?></p>
                            <p class="card-text">Avion: <?php echo $v->getAvion()->getModelo(); ?></p>
                        </div>
                    </div>
                    <?php 
                        if(isset($_SESSION['id'])){
                                if ($_SESSION['rol'] == 'pasajero'){
                    ?>
                        <div id="planesVuelo">
                            <div class="card-footer vuelo" data-id="<?php $v->getIdVuelo() ?>">
                                <p class="btn btn-info w-100">Reservar</p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="alert alert-info" role="alert">
                        USted es un <?php echo $_SESSION["rol"]=="piloto"? "Piloto": "Adminitrador" ?> por ende no puede reservar un vuelo
                    </div>
                    <?php
                        }else{
                    ?>
                        <div class="card-footer">
                        <form action="?pid=<?php echo base64_encode('Login'); ?>" method="POST">
                            <button type="submit" class="btn btn-info w-100" name="reservarVuelo">
                                Reservar
                            </button>
                        </form>
                    </div>
                    <?php } ?>
                </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>

    </div>
</div>
