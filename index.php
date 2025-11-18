<?php
// Unificar el nombre de sesión en TODA la app
session_name("AERO_SESSION");
session_start();


//zona para los inclue once de las clases
include_once("config/Conexion.php");
include_once("config/Seguridad.php");
include_once("dao/PasajeroDAO.php");
include_once("modelo/Persona.php");
include_once("modelo/Pasajero.php");

//este campo me sirvio para dar una capa mas de seguridad para quer 
//no se pueda acceder a las vistas directamente ni que se viera la 
//estructura de carpetas no si te paresca par a mejorar la seguridad

$pages = [
    "Home" => "views/Home.php",
    "Error" => "views/Error.php",
    "Registrar" => "views/RegistroPasajero.php",
    "Activar" => "views/Activacio.php"
];

// Página por defecto
$page = isset($_GET['pid']) ? base64_decode($_GET['pid']) : 'Home';

// Cerrar sesión
if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


//para que se pudiera acceder a vistas que no requiere la variable de session id
$vistasPublicas = ["Login", "Registrar", "Error", "Activar", "Home"];
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    // Si no hay sesión y la vista no es pública, redirigir a Error
    if (!in_array($page, $vistasPublicas)) {
        $page = "Error";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AeroNova</title>
     <link rel="icon" href="favicon.ico" type="image/x-icon"><!-- falta agregar el icono de la pagina  -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

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
