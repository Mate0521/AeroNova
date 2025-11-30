<?php

use Vtiful\Kernel\Format;
// Unificar el nombre de sesión en TODA la app
session_name("AERO_SESSION");
session_start();

// Zona de includes de clases
include_once("config/Conexion.php");
include_once("config/Seguridad.php");
include_once("modelo/Admin.php");
include_once("modelo/Avion.php");
include_once("modelo/Ciudad.php");
include_once("modelo/Estado.php");
include_once("modelo/Pasajero.php");
include_once("modelo/Piloto.php");
include_once("modelo/Ruta.php");
include_once("modelo/Ticket.php");
include_once("modelo/Vuelo.php");
require_once( "fpdf/fpdf.php");//pdf
require_once("phpqrcode/qrlib.php");//qr
require_once("component/AutomatizacionEstados.php");//cron
require_once ("config/env.php");//clave_api

try{
    AutomatizacionEstados::run();
}catch(Exception $e){
    echo $e;
}

// Lista de páginas disponibles
$pages = [
    "Home" => "views/Home.php",
    "Error" => "views/Error.php",
    "Registrar" => "views/RegistroPasajero.php",
    "Activar" => "views/Activacio.php",
<<<<<<< HEAD
    "panelAdmin" => "views/sesionAdmin.php",
    "panelPiloto" => "views/piloto/sesionPiloto.php",
    "panelPasajero" => "views/sesionPasajero.php",
    "panelVuelos" => "views/piloto/misvuelos.php",
    "Login" => "views/autenticar.php",
    "PanelDatosPiloto" => "views/piloto/PanelDatosPiloto.php",
    "HistorialVuelosPiloto" => "views/piloto/HistorialVuelosPiloto.php"
=======
    "panelAdmin"=>"views/sesionAdmin.php",
    "panelPiloto"=>"views/sesionPiloto.php",
    "panelPasajero"=>"views/Pasajero/PanelPasajero.php",
    "Login" => "views/autenticar.php",
    "reservarVuelo" => "views/Pasajero/CompraVuelo.php",
    "crearTikecket" => "views/Pasajero/CrearTikecket.php",
    "Checkin"=> "views/Pasajero/CheckIn.php",
    "constTick" => "views\Pasajero\ConsultarTickets.php",
    "dashboarad" => "views\Pasajero\Estadisticas.php"
>>>>>>> feature/Mateo
];

// Página por defecto
$page = isset($_GET['pid']) ? base64_decode($_GET['pid']) : 'Home';

// Cerrar sesión
if (isset($_POST["cerrarSecion"])) {
    session_unset();
    session_destroy();
    header("Location: ?pid=" . base64_encode("Home"));
    exit();
}

<<<<<<< HEAD
// Vistas públicas
$vistasPublicas = ["Login", "Registrar", "Error", "Activar", "Home"];

// Control de acceso por rol
$privadasPorRol = [
    "admin" => ["panelAdmin"],
    "piloto" => ["panelPiloto", "panelVuelos", "PanelDatosPiloto","HistorialVuelosPiloto"],
    "pasajero" => ["panelPasajero"]
];

// Si no hay sesión y la página no es pública -> Error
=======

//para que se pudiera acceder a vistas que no requiere la variable de session id
$vistasPublicas = ["Login", "Registrar", "Error", "Activar", "Home", "Checkin"];
>>>>>>> feature/Mateo
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    if (!in_array($page, $vistasPublicas)) {
        $page = "Error";
    }
<<<<<<< HEAD
} elseif (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    // Si la página no pertenece a su rol ni es pública -> Error
    if (!in_array($page, $privadasPorRol[$rol]) && !in_array($page, $vistasPublicas)) {
        $page = "Error";
    }
}

=======
}


>>>>>>> feature/Mateo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AeroNova</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-black">
    <div>
        <?php
            if (!in_array($page, ["Login", "Registrar"])) { 
                include('component/menu.php');
            }
        ?>
    </div>

    <div class="container mt-4 mb-4 text-center">
        <?php
            if (array_key_exists($page, $pages)) {
                include($pages[$page]);
            } else {
                include($pages["Error"]);
            }
        ?>
    </div>

<<<<<<< HEAD
    <div>
        <?php
            if (!in_array($page, ["Login", "Registrar"])) {
                include('component/footer.php');
            }
        ?>
    </div>
</body>
=======
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <script src="https://www.gstatic.com/charts/loader.js"></script>
    </head>
    <body class="">
        <div>
            <?php
                if ($page != "Login" && $page != "Registrar") { // paginas que no requieren que se muestre el menu
                    include('component/menu.php');
                }
            ?>
        </div>
        <div class="container mt-4 mb-4 text-center">
            <?php
                var_dump($_SESSION);
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
>>>>>>> feature/Mateo
</html>
