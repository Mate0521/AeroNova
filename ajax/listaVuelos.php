<?php 
require_once(__DIR__ . '/../modelo/Vuelo.php');
require_once(__DIR__ . '/../modelo/Avion.php');
require_once(__DIR__ . '/../modelo/Ruta.php');
require_once(__DIR__ . '/../modelo/Piloto.php');
require_once(__DIR__ . '/../modelo/Estado.php');

// Validaciones
if (!isset($_POST["estado"])) {
    echo "<p>No llegó el estado</p>";
    exit();
}

if (!isset($_POST["idPiloto"])) {
    echo "<p>No llegó el ID del piloto</p>";
    exit();
}

$estado = $_POST["estado"];
$idPiloto = $_POST["idPiloto"];

$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelosPorEstado($idPiloto, $estado);

if (!$vuelos || count($vuelos) == 0) {
    echo "<p>No hay vuelos con ese estado</p>";
    exit();
}

// Tabla con diseño moderno
echo '<div class="table-responsive">
<table class="table table-striped table-hover table-bordered text-light bg-dark align-middle">
    <thead class="table-secondary text-dark">
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Despegue</th>
            <th>Llegada</th>
            <th>Piloto</th>
            <th>Copiloto</th>
            <th>Avión</th>
            <th>Ruta</th>
            <th>Acciones / Estado</th>
        </tr>
    </thead>
    <tbody>';

foreach ($vuelos as $v) {
    $piloto = $v->getPilotoPrincipal();
    $copiloto = $v->getCopiloto();
    $avion = $v->getAvion();
    $ruta = $v->getRuta();
    $estadoVuelo = $v->getEstadoVuelo();

    echo "<tr>
        <td>{$v->getIdVuelo()}</td>
        <td>{$v->getFecha()}</td>
        <td>{$v->getHoraDespegue()}</td>
        <td>{$v->getHoraLlegada()}</td>
        <td>{$piloto->getNombre()}</td>
        <td>{$copiloto->getNombre()}</td>
        <td>{$avion->getModelo()} ({$avion->getMatricula()}, Cap: {$avion->getCapacidad()})</td>
        <td>{$ruta->getOrigen()->getNombre()} → {$ruta->getDestino()->getNombre()} ({$ruta->getDistanciaKM()} km)</td>
        <td>
            <div id='estado{$v->getIdVuelo()}'>";

           if ($estadoVuelo->getIdEstado() == 6) {
                echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-warning text-dark shadow-sm'>
                        <i class='bi bi-exclamation-circle me-1'></i> Solicitado
                      </div>";
           } else {
                echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-info text-dark shadow-sm'>
                        <i class='bi bi-info-circle me-1'></i> {$estadoVuelo->getValor()}
                      </div>";
           }

    echo "</div>";

    if ($estadoVuelo->getIdEstado() == 6) {
        echo "<div class='d-flex gap-1 mt-1'>
            <button id='aceptar{$v->getIdVuelo()}' class='btn btn-outline-success btn-sm'>
                <i class='bi bi-check-circle'></i> Aceptar
            </button>
            <button id='rechazar{$v->getIdVuelo()}' class='btn btn-outline-danger btn-sm'>
                <i class='bi bi-x-circle'></i> Rechazar
            </button>
        </div>";
    }

    echo "</td></tr>";
}

echo '</tbody></table></div>';
?>

<script>
// === GENERAR LOS EVENTOS PARA CADA VUELO ===
<?php 
foreach ($vuelos as $v) { 
    if ($v->getEstadoVuelo()->getIdEstado() == 6) {

        echo "$( '#aceptar".$v->getIdVuelo()."' ).on('click', function() {
                var url = 'ajax/cambiarEstadoVuelo.php?idVuelo=".$v->getIdVuelo()."&estado=8';
                $('#estado".$v->getIdVuelo()."').load(url);
                $('#aceptar".$v->getIdVuelo()."').hide();
                $('#rechazar".$v->getIdVuelo()."').hide();
        });\n";

        echo "$( '#rechazar".$v->getIdVuelo()."' ).on('click', function() {
                var url = 'ajax/cambiarEstadoVuelo.php?idVuelo=".$v->getIdVuelo()."&estado=7';
                $('#estado".$v->getIdVuelo()."').load(url);
                $('#aceptar".$v->getIdVuelo()."').hide();
                $('#rechazar".$v->getIdVuelo()."').hide();
        });\n";
    }
}
?>
</script>
