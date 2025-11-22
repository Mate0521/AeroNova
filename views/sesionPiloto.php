<?php
if ($_SESSION["rol"] != "piloto") {
    header("Location: /?error=acceso_denegado");
    exit();
}

if (isset($_SESSION["mensaje"])) {
    echo "<div class='alert alert-success'>".$_SESSION["mensaje"]."</div>";
    unset($_SESSION["mensaje"]);
}
?>
<h1>Bienvenido Piloto</h1>
<p>Contenido del panel…</p>
