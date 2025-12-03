<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);

    if ($nombre == "") {
        $mensaje = "<div class='alert alert-danger'>Debe ingresar un nombre.</div>";
    } else {

        $ciudad = new Ciudad(null, $nombre);
        $resultado = $ciudad->crear();

        if ($resultado === "ok") {
            $mensaje = "<div class='alert alert-success'>Ciudad creada correctamente</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error: $resultado</div>";
        }
    }
}
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Crear Ciudad</h4>
        </div>

        <div class="card-body">

            <?= $mensaje ?? "" ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nombre de la Ciudad</label>
                    <input type="text" id="city_input" name="nombre" class="form-control" placeholder="Escribe una ciudad..." required>
                </div>

                <div id="map" style="height: 350px; margin-top: 20px;" class="rounded shadow"></div>

                <button class="btn btn-success mt-4">Guardar Ciudad</button>
            </form>

        </div>
    </div>
</div>
<?php $googleKey=getenv("GOOGLE_MAPS_KEY") ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= $googleKey ?>&libraries=places"></script>
<script>
    let map;
    let marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 4.5709, lng: -74.2973 }, // Centro de Colombia
            zoom: 5
        });

        marker = new google.maps.Marker({
            map: map,
            draggable: false
        });

        // AUTOCOMPLETE SOLO PARA LLENAR EL NOMBRE
        const input = document.getElementById("city_input");
        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['(cities)']
        });

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            let lat = place.geometry.location.lat();
            let lng = place.geometry.location.lng();

            // Mover mapa al seleccionar una ciudad
            marker.setPosition({ lat, lng });
            map.setCenter({ lat, lng });
            map.setZoom(10);
        });

        // Click en el mapa SOLO mueve el pin (pero NO guarda coordenadas)
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
        });
    }

    window.onload = initMap;
</script>
