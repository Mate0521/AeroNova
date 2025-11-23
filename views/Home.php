<?php
$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelos();
?>

<div id="vuelos">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php foreach ($vuelos as $vuelo): ?>
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
                        <?php 
                            if(!isset($_SESSION['id'])){
                                    if ($_SESSION['rol'] == 'pasajero'):
                        ?>
                            <div>
                                <h3><span><?= !empty($precio) ? "$$precio" : "" ?></span></h3>
                            </div>
                            <div id="planesVuelo">
                                <div class="card-footer vuelo" data-id="<?php $vuelo->getIdVuelo() ?>">
                                    <p class="btn btn-info w-100">Reservar</p>
                                </div>
                            </div>
                        <?php 
                        
                        endif;
                        ?>
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
</div>
