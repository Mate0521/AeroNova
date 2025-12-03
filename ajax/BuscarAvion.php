<?php

require_once "../modelo/Avion.php";

$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";


$avion = new Avion();
$resultado = $avion->buscarAvion($q); 

if (count($resultado) == 0) {
    echo "<tr><td colspan='4' class='text-center text-muted'>Sin resultados</td></tr>";
    exit;
}

foreach ($resultado as $a) {
    echo "
    <tr data-row='{$a->getMatricula()}'>
        <td><strong>{$a->getMatricula()}</strong></td>

        <td class='edit-campo'
            data-id='{$a->getMatricula()}'
            data-campo='modelo'
            data-valor='{$a->getModelo()}'>
            {$a->getModelo()}
        </td>

        <td class='edit-campo'
            data-id='{$a->getMatricula()}'
            data-campo='capacidad'
            data-valor='{$a->getCapacidad()}'>
            {$a->getCapacidad()}
        </td>

        <td id='acciones_{$a->getMatricula()}'>
            <button class='btn btn-primary btn-sm ver_detalles'
                data-id='{$a->getMatricula()}'>
                <i class='bi bi-eye'></i> Historial
            </button>
        </td>
    </tr>
    ";
}
