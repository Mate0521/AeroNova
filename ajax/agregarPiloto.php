<?php
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

require_once("../modelo/Piloto.php");
require_once("../config/Conexion.php");

// Validar datos recibidos
if (
    !isset($_POST["id"], $_POST["nombre"], $_POST["apellido"], $_POST["correo"], $_POST["telefono"], $_POST["estado"])
) {
    echo json_encode([
        "status" => "error",
        "message" => "❌ Error: datos incompletos"
    ]);
    exit;
}

$id       = intval($_POST["id"]);
$nombre   = trim($_POST["nombre"]);
$apellido = trim($_POST["apellido"]);
$correo   = trim($_POST["correo"]);
$telefono = trim($_POST["telefono"]);
$idEstado = intval($_POST["estado"]);

// Procesar imagen
$fotoPath = null;
if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
    $nombreTmp = $_FILES["foto"]["tmp_name"];
    $nombreArchivo = time() . "_" . basename($_FILES["foto"]["name"]);
    $destino = "../uploads/" . $nombreArchivo;

    // Crear carpeta si no existe
    if (!is_dir("../uploads")) {
        mkdir("../uploads", 0777, true);
    }

    if (move_uploaded_file($nombreTmp, $destino)) {
        $fotoPath = "uploads/" . $nombreArchivo;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Error al guardar la imagen"
        ]);
        exit;
    }
}

try {
    // Crear objeto Piloto y asignar propiedades
    $piloto = new Piloto();
    $piloto->setId($id);
    $piloto->setNombre($nombre);
    $piloto->setApellido($apellido);
    $piloto->setCorreo($correo);
    $piloto->setTelefono($telefono);
    $piloto->setFoto($fotoPath);
    $piloto->setEstadoPiloto($idEstado);

    // Guardar en BD
    $resultado = $piloto->agregarPiloto();

    if ($resultado) {
        echo json_encode([
            "status" => "success",
            "message" => "✅ Piloto agregado correctamente"
        ]);
    } else {
        error_log("[AgregarPiloto] Falló el insert. Datos: ID=$id, Nombre=$nombre, Apellido=$apellido, Correo=$correo, Teléfono=$telefono, Estado=$idEstado, Foto=$fotoPath");
        echo json_encode([
            "status" => "error",
            "message" => "❌ Error al agregar piloto"
        ]);
    }
} catch (Exception $e) {
    error_log("[AgregarPiloto] Excepción: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "❌ Error interno: " . htmlspecialchars($e->getMessage())
    ]);
}
