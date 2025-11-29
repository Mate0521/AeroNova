<?php
// ===========================
//  ACCESO SOLO PARA PILOTOS
// ===========================
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "piloto") {
    header("Location: index.php?error=acceso_denegado");
    exit();
}

// ===========================
//   CARGAR MODELO
// ===========================
require_once("modelo/Piloto.php");

// Crear objeto y cargar datos
$piloto = new Piloto($_SESSION["id"]);
$piloto->obtenerPilotoId();

$mensaje = "";
$mensajePass = "";

// ===========================
//  ACTUALIZAR DATOS DEL PERFIL
// ===========================
if (isset($_POST["actualizar"])) {

    $nombre   = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo   = $_POST["correo"];
    $telefono = $_POST["telefono"];

    // FOTO ANTERIOR
    $nuevaFoto = $piloto->getFoto();

    // ===========================
    //  SUBIR NUEVA FOTO
    // ===========================
    if (!empty($_FILES["foto"]["name"])) {

        $carpeta = "uploads/pilotos/";

        // Crear carpetas si no existen
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nombreFoto = time() . "_" . basename($_FILES["foto"]["name"]);
        $ruta = $carpeta . $nombreFoto;

        // Validar que sea imagen
        $extensionesValidas = ["jpg", "jpeg", "png", "gif"];
        $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));

        if (in_array($extension, $extensionesValidas)) {

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta)) {
                $nuevaFoto = $ruta;
            } else {
                $mensaje = "<div class='alert alert-danger'>❌ Error al subir la foto.</div>";
            }

        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Solo se permiten imágenes (JPG, JPEG, PNG, GIF).</div>";
        }
    }

    // ===========================
    //  GUARDAR EN BD
    // ===========================
    require_once("config/Conexion.php");
    $conexion = new Conexion();
    $conexion->abrir();

    $sql = "UPDATE g2_piloto SET 
                Nombre = :n,
                Apellido = :a,
                Correo = :c,
                Telefono = :t,
                Foto = :f
            WHERE idPiloto = :id";

    $conexion->ejecutar($sql, [
        ":n"  => $nombre,
        ":a"  => $apellido,
        ":c"  => $correo,
        ":t"  => $telefono,
        ":f"  => $nuevaFoto,
        ":id" => $_SESSION["id"]
    ]);

    $conexion->cerrar();

    $mensaje = "<div class='alert alert-success'>✔ Datos actualizados correctamente.</div>";

    // Recargar datos
    $piloto->obtenerPilotoId();
}



// ===========================
//    CAMBIO DE CONTRASEÑA
// ===========================
if (isset($_POST["cambiarPass"])) {

    $actual = md5($_POST["actual"]);
    $nueva1 = $_POST["nueva1"];
    $nueva2 = $_POST["nueva2"];

    // Validar contraseña actual
    if ($actual !== $piloto->getClave()) {

        $mensajePass = "<div class='alert alert-danger'>❌ La contraseña actual no es correcta.</div>";

    } elseif ($nueva1 !== $nueva2) {

        $mensajePass = "<div class='alert alert-warning'>⚠ Las contraseñas nuevas no coinciden.</div>";

    } else {

        // Guardar nueva contraseña
        require_once("config/Conexion.php");
        $conexion = new Conexion();
        $conexion->abrir();

        $sql = "UPDATE g2_piloto SET Clave = :clave WHERE idPiloto = :id";
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
    <h2 class="text-center mb-4">Mis Datos Personales</h2>

    <?= $mensaje ?>

    <div class="row justify-content-center">

        <!-- FOTO Y ESTADO -->
        <div class="col-md-4 text-center">
            <img src="<?= $piloto->getFoto() != '' ? $piloto->getFoto() : 'images/default.png' ?>" 
                 class="img-thumbnail mb-3" width="180">

            <h4><?= $piloto->getNombre() . " " . $piloto->getApellido() ?></h4>

            <span class="badge bg-info fs-6">
                Estado: <?= $piloto->getEstadoPiloto()->getValor() ?>
            </span>
        </div>

        <!-- FORMULARIO DE DATOS -->
        <div class="col-md-6">

            <form method="POST" enctype="multipart/form-data">

                <h4 class="mb-3">Actualizar Información</h4>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= $piloto->getNombre() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Apellido</label>
                    <input type="text" name="apellido" class="form-control"
                           value="<?= $piloto->getApellido() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo</label>
                    <input type="email" name="correo" class="form-control"
                           value="<?= $piloto->getCorreo() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="<?= $piloto->getTelefono() ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Foto del Piloto</label>
                    <input type="file" name="foto" class="form-control">
                </div>

                <button type="submit" name="actualizar" class="btn btn-primary w-100 mb-4">
                    Guardar Cambios
                </button>

            </form>



            <!-- =======================
                    CAMBIAR CONTRASEÑA
            ======================== -->
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
