<?php
require_once(__DIR__ . '/../modelo/Vuelo.php');
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/VueloDAO.php');

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

// Mostrar errores en desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar buffer para evitar que se impriman warnings/notices antes del JSON
ob_start();

// Validar datos recibidos
if (!isset($_POST["pilotos"], $_POST["aviones"], $_POST["rutas"], $_POST["fechas"], $_POST["horasDespegue"])) {
    ob_clean();
    echo json_encode([
        "status" => "warning",
        "message" => "Faltan datos para crear los vuelos"
    ]);
    exit();
}

$pilotos       = $_POST["pilotos"];
$aviones       = $_POST["aviones"];
$rutas         = $_POST["rutas"];
$fechas        = $_POST["fechas"];
$horasDespegue = $_POST["horasDespegue"];
$horasLlegada  = $_POST["horasLlegada"] ?? [];
$copilotos     = $_POST["copilotos"] ?? [];

$counts = [count($pilotos), count($aviones), count($rutas), count($fechas), count($horasDespegue)];
if (count(array_unique($counts)) !== 1) {
    ob_clean();
    echo json_encode([
        "status" => "warning",
        "message" => "Los datos enviados no están alineados"
    ]);
    exit();
}

$creados = 0;
$errores = [];

foreach ($pilotos as $i => $idPiloto) {
    if (
        empty($idPiloto) ||
        empty($aviones[$i]) ||
        empty($rutas[$i]) ||
        empty($fechas[$i]) ||
        empty($horasDespegue[$i])
    ) {
        $errores[] = "Vuelo #".($i+1)." tiene datos incompletos.";
        continue;
    }

    $vuelo = new Vuelo();
    $vuelo->setPilotoPrincipal($idPiloto);
    $vuelo->setCopiloto($copilotos[$i] ?? null);
    $vuelo->setAvion($aviones[$i]);
    $vuelo->setRuta($rutas[$i]);
    $vuelo->setFecha($fechas[$i]);
    $vuelo->setHoraDespegue($horasDespegue[$i]);
    $vuelo->setHoraLlegada($horasLlegada[$i] ?? null);
    $vuelo->setEstadoVuelo(6);

    try {
        $resultado = $vuelo->crear();
        if ($resultado === true) {
            $creados++;
        } else {
            $errores[] = "Vuelo #".($i+1).": No se pudo confirmar la creación.";
        }
    } catch (Exception $e) {
        $errores[] = "Vuelo #".($i+1).": " . $e->getMessage();
    }
}

// Construir mensaje final
if ($creados > 0 && empty($errores)) {
    $status = "success";
    $message = "Se crearon correctamente $creados vuelo(s).";
} elseif ($creados > 0 && !empty($errores)) {
    $status = "warning";
    $message = "Se crearon $creados vuelo(s), pero algunos no se pudieron crear: " . implode(" | ", $errores);
} else {
    $status = "danger";
    $message = "No se pudo crear ningún vuelo. Errores: " . implode(" | ", $errores);
}

// Limpiar cualquier salida previa y devolver solo JSON
ob_clean();
echo json_encode([
    "status" => $status,
    "message" => $message
]);
exit();
