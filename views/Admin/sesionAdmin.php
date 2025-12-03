<?php

if ($_SESSION["rol"] != "admin") {
    header("Location: ?pid=".base64_encode("Error"));
    exit();
}

?>

<div class="card mt-3">
    <div class="card-header">
        <h4>Vuelos en Vivo</h4>
    </div>
    <div class="card-body">
        <iframe
            src="https://es.flightaware.com/live/?profile=1"
            width="100%"
            height="650px"
            style="border:none;">
        </iframe>
    </div>
</div>
