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
    "panelAdmin" => "views/Admin/sesionAdmin.php",
    "panelPiloto" => "views/piloto/sesionPiloto.php",
    "panelVuelos" => "views/piloto/misvuelos.php",
    "Login" => "views/autenticar.php",
    "PanelDatosPiloto" => "views/piloto/PanelDatosPiloto.php",
    "HistorialVuelosPiloto" => "views/piloto/HistorialVuelosPiloto.php",
    "panelPasajero"=>"views/Pasajero/PanelPasajero.php",
    "reservarVuelo" => "views/Pasajero/CompraVuelo.php",
    "crearTikecket" => "views/Pasajero/crearTikecket.php",
    "Checkin"=> "views/Pasajero/CheckIn.php",
    "constTick" => "views/Pasajero/ConsultarTickets.php",
    "dashboarad" => "views/Pasajero/Estadisticas.php",//--
    "administrarPasajeros"=>"views/Admin/AdminPasajeros.php", 
    "dashboarAdmin"=>"views/Admin/EstadisticasAdmin.php",
    'PanelAviones'=>"views/Admin/PanelAviones.php",
    'addAvion'=>"views/Admin/AdicionAvion.php",
    'PanelRutas'=>"views/Admin/PanelRutas.php",
    "addRuta"=>"views/Admin/AdicionRuta.php",
    "addCiudad"=>"views/Admin/AdicionCiudad.php",
    "PanelPilotoAdmin"=>"views/piloto/PanelPilotoAdmin.php",
    "SolicitarVuelo"=>"views/piloto/SolicitarVuelo.php",
    "verVuelosProgramados"=>"views/Vuelos/verVuelosProgramados.php",
    "solicitudcopiloto"=>"views/piloto/solicitudcopiloto.php",
    "PanelDatosAdmin"=>"views/Administrador/PanelDatosAdministrador.php",
    "PanelDatosPasajero"=>"views/Pasajero/PanelDatosPasajero.php",
    "vuelos"=>"views/Vuelos/Vuelos.php",
    "aviones"=>"views/Avion/Consultaraviones.php",
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


// Vistas públicas que NO requieren sesión
$vistasPublicas = ["Login", "Registrar", "Error", "Activar", "Home", "Checkin","vuelos","aviones"];

// Vistas privadas por rol
$privadasPorRol = [
    "admin" => ["panelAdmin", "administrarPasajeros", "dashboarAdmin", 'addAvion', 'PanelAviones', 'PanelRutas', "addRuta", "addCiudad","PanelPilotoAdmin","SolicitarVuelo","verVuelosProgramados","PanelDatosAdmin", "solicitudcopiloto"],
    "piloto" => ["panelPiloto", "panelVuelos", "PanelDatosPiloto", "HistorialVuelosPiloto"],
    "pasajero" => ["panelPasajero", "reservarVuelo", "crearTikecket", "Checkin", "constTick", "dashboarad","PanelDatosPasajero"]
];


// Si NO hay sesión → solo dejar entrar a vistas públicas
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {

    if (!in_array($page, $vistasPublicas)) {
        $page = "Error";
    }
// Si hay sesión, validar permisos por rol
} elseif (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];

    // Si la vista NO pertenece al rol y NO es pública → Error
    if (!in_array($page, $privadasPorRol[$rol]) && !in_array($page, $vistasPublicas)) {
        $page = "Error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AeroNova</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body class="bg-black text-white">

    <!-- MENU (no aparece en Login/Registrar) -->
    <div>
        <?php 
            if (!in_array($page, ["Login", "Registrar"])) {
                include('component/menu.php');
            }
        ?>
    </div>

    
    <!-- CONTENIDO PRINCIPAL -->
    <div class="container mt-4 mb-4 text-center">
        <?php
            if (array_key_exists($page, $pages)) {
                include($pages[$page]);
            } else {
                include($pages["Error"]);
            }
        ?>
    </div>

    <!-- FOOTER (no aparece en Login/Registrar) -->
    <div>
        <?php
            if (!in_array($page, ["Login", "Registrar"])) {
                include('component/footer.php');
            }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>