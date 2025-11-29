<?php

if ($_SESSION["rol"] != "admin") {
    header("Location: /?error=acceso_denegado");
    exit();
}

if (isset($_SESSION["mensaje"])) {
    echo "<div class='alert alert-success'>".$_SESSION["mensaje"]."</div>";
    unset($_SESSION["mensaje"]);
}
?>

