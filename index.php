<?php
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
if (isset($_POST["cerrarSecion"])) {
    session_destroy();
    header("Location: ?pid=". base64_encode("Home"));
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
</head>

<body>
    <div>
        <?php
            if ($page != "Login" && $page != "Registrarse") { // paginas que no requieren que se muestre el menu
                include('component/menu.php');
            }
        ?>
    </div>
    <div>
        <?php
            if (array_key_exists($page, $pages)) {
                include($pages[$page]);
            } else {
                include($pages["Error"]);
            }
        ?>

    </div>
    <div>
        <?php
            if ($page != "Login" && $page != "Registrarse") {// paginas que no requieren que se muestre el footer
                include('component/footer.php');
            }
        ?>
    </div>
    
</body>

</html>