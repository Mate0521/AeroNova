<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Acceso solo para pilotos
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "piloto") {
    header("Location: index.php?error=acceso_denegado");
    exit();
}

// === AQUI AGREGAMOS EL ID DEL PILOTO DESDE LA SESIÓN ===
$idPiloto = $_SESSION["id"] ?? null;

if ($idPiloto === null) {
    echo "<p class='text-danger'>❌ ERROR: No se encontró el ID del piloto en la sesión.</p>";
    exit();
}

require_once __DIR__ . "/../../modelo/Estado.php";
$estadoObj = new Estado();
$estados = $estadoObj->obtenerEstadoVuelo(); 
?>

<!-- PASAMOS EL ID DEL PILOTO A JAVASCRIPT -->
<script>
    const idPiloto = <?= $idPiloto ?>;
</script>

<div class="container mt-4 text-white">
    <h3 class="mb-4">Mis Vuelos</h3>

    <div class="mb-4">
        <label class="form-label">Estado del Vuelo</label>
        <select id="selectEstado" class="form-select">
            <option value="">Seleccione un estado...</option>
            <?php foreach($estados as $e): ?>
                <option value="<?= $e["id_estado"] ?>"><?= $e["nombre_estado"] ?></option>
            <?php endforeach; ?>
        </select>

        <!-- 🔍 BUSCADOR AQUÍ -->
        <input type="text" id="buscarVuelo" 
               class="form-control mt-3" 
               placeholder="Buscar por fecha, ruta, avión...">

        <div id="tablaVuelos" class="mt-3"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // CUANDO CAMBIA EL ESTADO
    $("#selectEstado").on("change", cargarVuelos);

    // FILTRADO EN TIEMPO REAL
    $("#buscarVuelo").on("keyup", function() {
        let texto = $(this).val().toLowerCase();

        $("#tablaVuelos table tbody tr").filter(function() {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(texto) > -1
            );
        });
    });

    function cargarVuelos() {
        let estado = $("#selectEstado").val();

        if (estado === "") {
            $("#tablaVuelos").html(""); 
            return;
        }

        $.post("ajax/listaVuelos.php", 
        { 
            estado: estado,
            idPiloto: idPiloto 
        }, 
        function(respuesta) {
            $("#tablaVuelos").html(respuesta);
        }).fail(function() {
            $("#tablaVuelos").html("❌ ERROR: No se pudo acceder a ajax/listaVuelos.php");
        });
    }
});
</script>
