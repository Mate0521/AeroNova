<?php
require_once("../modelo/Pasajero.php");

$q = $_GET["q"] ?? "";

$pasajero = new Pasajero();
$resultados = $pasajero->buscarPasajero($q);

$html = "";

foreach ($resultados as $c) {

    $id = $c->getId();
    $estado = $c->getEstadoCuenta();

    $icono = $estado == 1 
        ? "<span><i class='bi bi-check-circle text-success'></i> Activo</span>"
        : "<span><i class='bi bi-x-circle text-danger'></i> Inactivo</span>";

    $html .= "
    <tr data-row='$id'>

        <td class='edit-campo' data-id='$id' data-campo='nombre' data-valor='{$c->getNombre()}'>{$c->getNombre()}</td>

        <td class='edit-campo' data-id='$id' data-campo='apellido' data-valor='{$c->getApellido()}'>{$c->getApellido()}</td>

        <td class='edit-campo' data-id='$id' data-campo='correo' data-valor='{$c->getCorreo()}'>{$c->getCorreo()}</td>

        <td class='edit-campo' data-id='$id' data-campo='telefono' data-valor='{$c->getTelefono()}'>{$c->getTelefono()}</td>

        <td>
            <div id='estado_$id' data-id='$id' data-estado='$estado' class='estado-toggle'>
                $icono
            </div>
        </td>

        <td class='acciones' id='acciones_$id'>
            <button class='btn btn-success btn-sm' id='ver_t_$id' data-id='$id'>
                <i class='bi bi-ticket-perforated'></i> Ver Tickets
            </button>
        </td>

    </tr>";
}

echo $html === "" 
    ? "<tr><td colspan='6' class='text-center text-muted'>Sin resultados...</td></tr>"
    : $html;
