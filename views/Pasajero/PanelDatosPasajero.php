<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "pasajero") {
    header("Location: index.php?error=acceso_denegado");
    exit();
}

require_once("modelo/Pasajero.php");

// Crear objeto y cargar datos
$pasajero = new Pasajero($_SESSION["id"]);
$pasajero->obtenerPasajeroId();

$mensaje = "";
$mensajePass = "";

// ===========================
//    ACTUALIZAR DATOS
// ===========================
if (isset($_POST["actualizar"])) {
    $nombre   = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo   = $_POST["correo"];
    $telefono = $_POST["telefono"];

    require_once("config/Conexion.php");
    $conexion = new Conexion();
    $conexion->abrir();

    $sql = "UPDATE g2_pasajero SET 
                Nombre = :n,
                Apellido = :a,
                Correo = :c,
                Telefono = :t
            WHERE idPasajero = :id";

    $conexion->ejecutar($sql, [
        ":n"  => $nombre,
        ":a"  => $apellido,
        ":c"  => $correo,
        ":t"  => $telefono,
        ":id" => $_SESSION["id"]
    ]);

    $conexion->cerrar();

    $mensaje = "<div class='alert alert-success'>✔ Datos actualizados correctamente.</div>";

    // Recargar datos
    $pasajero->obtenerPasajeroId();
}

// ===========================
//    CAMBIO DE CONTRASEÑA
// ===========================
if (isset($_POST["cambiarPass"])) {

    // contraseña actual ingresada por el usuario (md5)
    $actualIngresada = md5($_POST["actual"]);

    // contraseña real desde la BD
    $actualBD = $pasajero->getClave();

    $nueva1 = $_POST["nueva1"];
    $nueva2 = $_POST["nueva2"];

    // Validación contraseña actual
    if ($actualIngresada !== $actualBD) {
        $mensajePass = "<div class='alert alert-danger'>❌ La contraseña actual no es correcta.</div>";
    }
    elseif ($nueva1 !== $nueva2) {
        $mensajePass = "<div class='alert alert-warning'>⚠ Las contraseñas nuevas no coinciden.</div>";
    }
    else {
        require_once("config/Conexion.php");
        $conexion = new Conexion();
        $conexion->abrir();

        $sql = "UPDATE g2_pasajero 
                SET Clave = :clave 
                WHERE idPasajero = :id";

        $conexion->ejecutar($sql, [
            ":clave" => md5($nueva1),
            ":id"    => $_SESSION["id"]
        ]);

        $conexion->cerrar();

        $mensajePass = "<div class='alert alert-success'>✔ Contraseña actualizada correctamente.</div>";
    }
}
?>

<!-- ===========================
        VISTA HTML
=========================== -->
<div class="container mt-5 text-white">
    <h2 class="text-center mb-4">Mis Datos Personales (Pasajero)</h2>

    <?= $mensaje ?>

    <div class="row justify-content-center">
        <div class="col-md-4 text-center">
            <h4><?= $pasajero->getNombre() . " " . $pasajero->getApellido() ?></h4>
        </div>

        <div class="col-md-6">
            <form method="POST">
                <h4 class="mb-3">Actualizar Información</h4>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= $pasajero->getNombre() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Apellido</label>
                    <input type="text" name="apellido" class="form-control"
                           value="<?= $pasajero->getApellido() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo</label>
                    <input type="email" name="correo" class="form-control"
                           value="<?= $pasajero->getCorreo() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="<?= $pasajero->getTelefono() ?>" required>
                </div>

                <button type="submit" name="actualizar" class="btn btn-primary w-100 mb-4">
                    Guardar Cambios
                </button>
            </form>

            <hr class="text-white">

            <h4 class="mt-4">Cambiar Contraseña</h4>
            <?= $mensajePass ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña Actual</label>
                    <input type="password" name="actual" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nueva Contraseña</label>
                    <input type="password" name="nueva1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" name="nueva2" class="form-control" required>
                </div>

                <button type="submit" name="cambiarPass" class="btn btn-warning w-100">
                    Actualizar Contraseña
                </button>
            </form>
        </div>
    </div>
</div>
