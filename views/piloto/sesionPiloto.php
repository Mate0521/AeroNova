<?php
if ($_SESSION["rol"] != "piloto") {
    header("Location: /?error=acceso_denegado");
    exit();
}


?>

