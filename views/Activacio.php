<?php 
if(isset($_GET["c"]) || isset($_POST["codigoVerificacion"])){
    $correo = base64_decode($_GET["c"]);
    $pasajero = new Pasajero(null, null, null, $correo, null, null, null);
    $pasajero->consultaPorCorreo();
    $codigoVerificacion = $_POST["codigoVerificacion"];
    if($pasajero->varificarCodigoVerificacion($codigoVerificacion)){
        $pasajero->activarCuenta();
        echo "<div class='alert alert-success' role='alert'>
                Cuenta activada correctamente. Ahora puede iniciar sesión.
              </div>";
    }else{
        echo "<div class='alert alert-danger' role='alert'>
                Código de verificación incorrecto. Por favor, intente de nuevo.
              </div>";
    }
?>
<div>
    <h2>Activación de cuenta</h2>
    <form method="post" action="?pid=<?php echo base64_encode("Activar"); ?>">
        <div class="mb-3">
            <label for="codigoVerificacion" class="form-label">Código de verificación</label>
            <input type="text" class="form-control"  name="codigoVerificacion" required>
        </div>
        <input type="hidden" name="correo" value="<?php echo $correo ?>">
        <button type="submit" class="btn btn-primary">Activar cuenta</button>
    </form>
</div>

<?php    
} else {
    echo "<div class='alert alert-warning' role='alert'>
            Correo no proporcionado, por favor verifique su enlace de activación.
          </div>";
    include('Error.php');
}
?>