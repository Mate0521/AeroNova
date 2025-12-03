<?php 

require_once(__DIR__ . "/../../config/Conexion.php");
require_once(__DIR__ . "/../../modelo/Piloto.php");
require_once(__DIR__ . "/../../modelo/Avion.php");
require_once(__DIR__ . "/../../modelo/Ruta.php");
require_once(__DIR__ . "/../../modelo/Estado.php");
require_once(__DIR__ . "/../../modelo/Vuelo.php");

if (session_status() === PHP_SESSION_NONE) session_start();

$idCopiloto = $_SESSION["id"]; 
$vuelo = new Vuelo();

// Traer vuelos en estado 8 (Pendiente por copiloto)
$vuelos = $vuelo->consultarVuelosPendienteCopiloto($idCopiloto, 8);

if (!$vuelos || count($vuelos) == 0) {
    echo "<p>No tienes solicitudes pendientes como copiloto</p>";
    exit();
}

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
        <td>".($copiloto ? $copiloto->getNombre() : "Sin asignar")."</td>
        <td>{$avion->getModelo()} ({$avion->getMatricula()})</td>
        <td>{$ruta->getOrigen()->getNombre()} → {$ruta->getDestino()->getNombre()}</td>
        <td>
            <div id='estado{$v->getIdVuelo()}'>";

    if ($estadoVuelo->getIdEstado() == 8) {
        echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-warning text-dark shadow-sm'>
                <i class='bi bi-person-plus me-1'></i> Pendiente por copiloto
              </div>";

        echo "<div class='d-flex gap-1 mt-1'>
            <button id='aceptar{$v->getIdVuelo()}' class='btn btn-outline-success btn-sm'>
                <i class='bi bi-check-circle'></i> Aceptar
            </button>
            <button id='rechazar{$v->getIdVuelo()}' class='btn btn-outline-danger btn-sm'>
                <i class='bi bi-x-circle'></i> Rechazar
            </button>
        </div>";
    } else {
        echo "<div class='d-inline-flex align-items-center px-3 py-1 rounded-pill bg-info text-dark shadow-sm'>
                <i class='bi bi-info-circle me-1'></i> {$estadoVuelo->getValor()}
              </div>";
    }

    echo "</div></td></tr>";
}

echo '</tbody></table></div>';
?>

<script>
<?php 
foreach ($vuelos as $v) { 
    if ($v->getEstadoVuelo()->getIdEstado() == 8) {
        // Botón aceptar → llama a SolicitudCopiloto con accion=aceptar
        echo "$( '#aceptar".$v->getIdVuelo()."' ).on('click', function() {
                var url = '/ajax/SolicitudCopiloto.php?idVuelo=".$v->getIdVuelo()."&accion=aceptar&idCopiloto=".$idCopiloto."';
                $('#estado".$v->getIdVuelo()."').load(url);
                $('#aceptar".$v->getIdVuelo()."').hide();
                $('#rechazar".$v->getIdVuelo()."').hide();
        });\n";

        // Botón rechazar → llama a SolicitudCopiloto con accion=rechazar
        echo "$( '#rechazar".$v->getIdVuelo()."' ).on('click', function() {
                var url = '/ajax/SolicitudCopiloto.php?idVuelo=".$v->getIdVuelo()."&accion=rechazar';
                $('#estado".$v->getIdVuelo()."').load(url);
                $('#aceptar".$v->getIdVuelo()."').hide();
                $('#rechazar".$v->getIdVuelo()."').hide();
        });\n";
    }
}
?>
</script>
