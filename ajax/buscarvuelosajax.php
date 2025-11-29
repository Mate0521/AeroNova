<?php
require_once(__DIR__ . '/../modelo/Vuelo.php');
require_once(__DIR__ . '/../modelo/Piloto.php');
require_once(__DIR__ . '/../modelo/Avion.php');
require_once(__DIR__ . '/../modelo/Ruta.php');
require_once(__DIR__ . '/../modelo/Estado.php');

// RECIBIR FILTRO
$filtro = isset($_GET["filtro"]) ? $_GET["filtro"] : "";
$filtro = str_replace("%20", " ", $filtro);

$vuelo = new Vuelo();
$vuelos = $vuelo->buscar($filtro);

// SI NO HAY RESULTADOS
if (count($vuelos) == 0) {
    echo "<div class='alert alert-warning' role='alert'>
           No hay vuelos que coincidan con la búsqueda.
          </div>";
    exit;
}
?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Hora Despegue</th>
            <th>Copiloto</th>
            <th>Avión</th>
            <th>Ruta</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($vuelos as $v): ?>

            <?php
            // Copiloto seguro
            $cop = $v->getCopiloto();
            $nombreCop = $cop ? $cop->getNombre() : "Sin copiloto";

            // Ruta segura
            $ruta = $v->getRuta();
            $origen = ($ruta && $ruta->getOrigen()) ? $ruta->getOrigen()->getNombre() : "¿?";
            $destino = ($ruta && $ruta->getDestino()) ? $ruta->getDestino()->getNombre() : "¿?";

            // Estado seguro
            $estado = $v->getEstadoVuelo();
            $estadoTexto = is_object($estado) ? $estado->getValor() : $estado;
            ?>

            <tr>
                <td><?= $v->getIdVuelo() ?></td>
                <td><?= $v->getFecha() ?></td>
                <td><?= $v->getHoraDespegue() ?></td>
                <td><?= $nombreCop ?></td>
                <td><?= $v->getAvion()->getMatricula() . " - " . $v->getAvion()->getModelo() ?></td>
                <td><?= $origen . " → " . $destino ?></td>
                <td><?= $estadoTexto ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>
