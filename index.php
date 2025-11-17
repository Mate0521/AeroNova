<?php
// Unificar el nombre de sesión en TODA la app
session_name("AERO_SESSION");
session_start();

require_once("modelo/Persona.php");
require_once("modelo/Admin.php");

// Cerrar sesión
if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AeroNova</title>

    <!-- Bootstrap + jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php
// -------------------------------------
// 1. Página por defecto
// -------------------------------------
if (!isset($_GET["pid"])) {
    include("views/inicio.php");
    exit();
}

// -------------------------------------
// 2. Recibir pid directamente SIN base64
// -------------------------------------
$pid = $_GET["pid"];

// -------------------------------------
// 3. Seguridad: solo permitir vistas válidas
// -------------------------------------
$allowed = [
    "views/autenticar.php",
    "views/sesionAdmin.php",
    "views/inicio.php"
];

if (!in_array($pid, $allowed)) {
    echo "<div class='alert alert-danger m-3'>Página no permitida.</div>";
    include("views/inicio.php");
    exit();
}

// -------------------------------------
// 4. Control de acceso: si hay sesión → mostrar vista
// -------------------------------------
if (isset($_SESSION["id"])) {
    include($pid);
} else {
    // Si no hay sesión, ir a autenticar sí o sí
    include("views/autenticar.php");
}
?>

</body>
</html>
