<?php
// empezar sesión antes de cualquier salida
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . "/../modelo/Persona.php");
require_once(__DIR__ . "/../modelo/Admin.php");

// mostrar errores en desarrollo (quita en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = 0;

if (isset($_POST["autenticar"])) {
    $correo = $_POST["correo"] ?? '';
    $clave  = $_POST["clave"] ?? '';

    $admin = new Admin("", "", "", $correo, $clave);

    if ($admin->autenticar()) {
        $_SESSION["id"] = $admin->getId();
        $_SESSION["rol"] = "admin";
        $_SESSION["mensaje"] = "¡Credenciales correctas!";

    header('Location: /index.php?pid=views/sesionAdmin.php');
        exit();
    } else {
        $error = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>AeroNova - Autenticar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
<form method="post" action="/index.php?pid=views/autenticar.php">
            <div class="mb-3">
              <input type="email" class="form-control" name="correo" placeholder="Correo" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="clave" placeholder="Clave" required>
            </div>
            <div class="mb-3">
              <button type="submit" class="btn btn-primary" name="autenticar">Autenticar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
