<?php

// mostrar errores en desarrollo (quita en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = 0;

if (isset($_POST["autenticar"])) {
    $correo = $_POST["correo"] ?? '';
    $clave  = $_POST["clave"] ?? '';

    $admin = new Admin("", "", "", $correo, "",$clave);

    if ($admin->autenticar()) {
        $_SESSION["id"] = $admin->getId();
        $_SESSION["rol"] = "admin";
        $_SESSION["mensaje"] = "¡Credenciales correctas!";
        header('Location: ?pid='. base64_encode('panelAdmin'));
        exit();
    } else {
        $error = 1;
    }
}
?>

<div class="container">
  <div class="row mt-5">
    <div class="col-4"></div>
    <div class="col-4">
      <div class="card">
        <div class="card-header"><h3>Autenticar</h3></div>
        <div class="card-body">
          <?php
          if ($error == 1) {
              echo "<div class='alert alert-danger' role='alert'>Correo o clave incorrectos</div>";
          }
          ?>
          <form method="post" action="?pid=<?php echo base64_encode("Login") ?>">
            <div class="mb-3">
              <input type="email" class="form-control" name="correo" placeholder="Correo" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="clave" placeholder="Clave" required>
            </div>
            <div class="mb-3">
              <button type="submit" class="btn btn-outline-secondary" name="autenticar">Autenticar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

