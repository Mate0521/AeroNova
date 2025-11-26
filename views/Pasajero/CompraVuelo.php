<?php

if (!isset($_SESSION['id'])) {
    header("Location: ?pid=" . base64_encode("Login"));
    exit();
}

$vuelo = null;
$precio = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);

    if (!isset($_POST['idVuelo']) || !isset($_POST['clase'])) {
        echo "<div class='alert alert-warning'>No se encontro var</div>";
        exit();
    }

    $idVuelo = intval($_POST['idVuelo']);
    $clase = $_POST['clase'];

    var_dump($_SESSION["v$idVuelo"]["precio"]);

    if (!isset($_SESSION["v$idVuelo"]["precio"])) {
        echo "<div class='alert alert-warning'>No se encontraro el precio</div>";
        exit();
    }

    $precio = $_SESSION["v$idVuelo"]["precio"];

    $multiplicadores = [
        "eco"  => 1.00,
        "clas" => 1.15,
        "bus"  => 1.40
    ];

    if (!isset($multiplicadores[$clase])) {
        header('Location: ?pid=' . base64_encode('panelPasajero'));
        exit();
    }
    $precio *= $multiplicadores[$clase];

    $vuelo = new Vuelo($idVuelo);
    $vuelo->obtenerVueloId();

    $puesto = $vuelo->asignarAsiento($clase);

    $ticket = new Ticket(
        "",
        1,
        $precio,
        $puesto,
        $_SESSION["id"],
        $vuelo,
        0
    );
}

if ($vuelo === null && isset($_GET['idV'])) {
    $idVuelo = base64_decode($_GET['idV']);
    $vuelo = new Vuelo($idVuelo);
    $vuelo->obtenerVueloId();
}
?>

<?php if ($vuelo) : ?>
<div class="row mb-3"> 
    <div class="card mb-3 bg-dark text-light border border-white w-100">
        <div class="row g-0">
            
            <div class="col-md-4">
                <img src="assets/img/vuelo.jpg" class="img-fluid rounded-start" alt="Imagen vuelo">
            </div>

            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title text-info fw-bold">
                        <?= $vuelo->getRuta()->getOrigen()->getNombre() ?>
                        -
                        <?= $vuelo->getRuta()->getDestino()->getNombre() ?>
                    </h5>

                    <p class="card-text">Fecha: <?= $vuelo->getFecha() ?></p>
                    <p class="card-text">Hora de Despegue: <?= $vuelo->getHoraDespegue() ?></p>
                    <p class="card-text">Avión: <?= $vuelo->getAvion()->getModelo() ?></p>
                </div>

                <?php if ($precio !== null): ?>
                <div class="card-footer text-center">
                    <h3 class="fw-bold text-success">$<?= number_format($precio, 0, ',', '.') ?></h3>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<div class="text-center">
    <form method="post" action="?pid=<?= base64_encode("crearTikecket") ?>">
        <input type="hidden" name="idVuelo" value="<?= $idVuelo ?>">
        <input type="hidden" name="clase" value="<?= $clase ?>">
        <input type="hidden" name="precio" value="<?= $precio ?>">
        <button type="submit" class="btn btn-success btn-lg px-5">Comprar</button>
    </form>
</div>

<?php endif; ?>

