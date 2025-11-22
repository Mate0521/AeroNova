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
include_once("dao/AdminDAO.php");
include_once("modelo/Admin.php");
include_once("dao/PilotoDAO.php");
include_once("modelo/Piloto.php");
include_once("dao/AvionDAO.php");
include_once("modelo/Avion.php");
include_once("dao/RutaDAO.php");
include_once("modelo/Ruta.php");
include_once("dao/EstadoDAO.php");
include_once("modelo/Estado.php");
include_once("dao/VueloDAO.php");
include_once("modelo/Vuelo.php");
include_once("dao/TicketDAO.php");
include_once("modelo/Ticket.php");
include_once("dao/CiudadDAO.php");
include_once("modelo/Ciudad.php");

//este campo me sirvio para dar una capa mas de seguridad para quer 
//no se pueda acceder a las vistas directamente ni que se viera la 
//estructura de carpetas no si te paresca par a mejorar la seguridad

$pages = [
    "Home" => "views/Home.php",
    "Error" => "views/Error.php",
    "Registrar" => "views/RegistroPasajero.php",
    "Activar" => "views/Activacio.php",
    "panelAdmin"=>"views/sesionAdmin.php",
    "panelPiloto"=>"views/sesionPiloto.php",
    "panelPasajero"=>"views/sesionPasajero.php",
    "Login" => "views/autenticar.php"

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

if (isset($_SESSION["rol"])) {

    if ($_SESSION["rol"] == "admin" && $page != "panelAdmin" && !in_array($page, $vistasPublicas)) {
        $page = "Error";
    }

    if ($_SESSION["rol"] == "piloto" && $page != "panelPiloto" && !in_array($page, $vistasPublicas)) {
        $page = "Error";
    }

    if ($_SESSION["rol"] == "pasajero" && $page != "panelPasajero" && !in_array($page, $vistasPublicas)) {
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
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="bg-black">
        <div>
            <?php
                if ($page != "Login" && $page != "Registrar") { // paginas que no requieren que se muestre el menu
                    include('component/menu.php');
                }
            ?>
        </div>
        <div clases="container mt-4 mb-4 text-center">
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
