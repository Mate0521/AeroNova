<?php
require_once "../modelo/Ruta.php";
require_once "../modelo/Ciudad.php";

$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";

$ruta = new Ruta();
$resultado = $ruta->buscarRuta($q);


if (count($resultado) == 0) {
    echo "<tr><td colspan='6' class='text-center text-muted'>Sin resultados</td></tr>";
    exit;
}

foreach ($resultado as $r) {

    $id = $r->getIdRuta();
    $origen = $r->getOrigen();
    $destino = $r->getDestino();

    echo "
    <tr data-row='{$id}'>

        <td>{$id}</td>

        <td class='edit-campo-select'
            data-id='{$id}'
            data-campo='origen'
            data-valor='{$origen->getId()}'>
            {$origen->getNombre()}
        </td>

        <td class='edit-campo-select'
            data-id='{$id}'
            data-campo='destino'
            data-valor='{$destino->getId()}'>
            {$destino->getNombre()}
        </td>

        <td class='edit-campo'
            data-id='{$id}'
            data-campo='duracion_estimada'
            data-valor='{$r->getDuracionEstimada()}'>
            {$r->getDuracionEstimada()}
        </td>

        <td class='edit-campo'
            data-id='{$id}'
            data-campo='distancia_KM'
            data-valor='{$r->getDistanciaKM()}'>
            {$r->getDistanciaKM()}
        </td>

        <td id='acciones_{$id}'>
            <button class='btn btn-secondary btn-sm btn-info-ruta'
                    data-id='{$id}'>
                <i class='bi bi-map'></i> Info
            </button>
        </td>

    </tr>
    ";
}
