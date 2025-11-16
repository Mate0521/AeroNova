<?php 
if (!isset($_GET["c"])) {
    echo "<div class='alert alert-warning' role='alert'>
            Correo no proporcionado, por favor verifique su enlace de activación.
          </div>";
    include('Error.php');
    return;
}

$correo = base64_decode($_GET["c"]);


$pasajero = new Pasajero(null, null, null, $correo, null, null, null);


if (!$pasajero->consultaPorCorreo()) {
    echo "<div class='alert alert-danger'>No se encontró el pasajero.</div>";
    return;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["codigoVerificacion"])) {

    $codigoVerificacion = trim($_POST["codigoVerificacion"]);

    if ($pasajero->varificarCodigoVerificacion($codigoVerificacion)) {

        $pasajero->activarCuenta();

        echo "<div class='alert alert-success'>
                Cuenta activada correctamente. Ahora puede iniciar sesión.
              </div>";

    } else {

        echo "<div class='alert alert-danger'>
                Código de verificación incorrecto. Por favor intente de nuevo.
              </div>";
    }
}

?>
<div>
    <h2>Activación de cuenta</h2>
    <form method="post" action="?pid=<?php echo base64_encode("Activar"); ?>&c=<?php echo $_GET["c"]; ?>">
        <div class="mb-3">
            <label for="codigoVerificacion" class="form-label">Código de verificación</label>
            <input type="text" class="form-control"  name="codigoVerificacion" required>
        </div>
        <input type="hidden" name="correo" value="<?php echo $correo ?>">
        <button type="submit" class="btn btn-primary">Activar cuenta</button>
    </form>
</div>
