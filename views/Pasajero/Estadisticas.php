<?php
$googleKey = $_ENV["GOOGLE_MAPS_KEY"];

$pasajero = new Pasajero($_SESSION["id"]);
$ticket = new Ticket(null, null, null, null,    $pasajero->getId());

$destinos = $ticket->obtenerDestinosFrecuentes();
$meses = $ticket->obtenerVuelosPorMes();

?>
<head>

<script>

google.charts.load('current', {
    'packages': ['geochart', 'corechart'],
    'mapsApiKey': '<?= $googleKey ?>'
});

google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    drawDestinosChart();
    drawVuelosMesChart();
}

function drawDestinosChart() {
    let data = google.visualization.arrayToDataTable([
        ["City", "Viajes"]
        <?php foreach($destinos as $d): ?>,
            ["<?= $d['ciudad'] ?>", <?= $d['cantidad'] ?>]
        <?php endforeach; ?>
    ]);

    let options = {
        region: "CO",
        displayMode: "markers",
        colorAxis: { colors: ["lightblue", "blue"] }
    };

    let chart = new google.visualization.GeoChart(document.getElementById("chart_destinos"));
    chart.draw(data, options);
}

function drawVuelosMesChart() {
    let data = google.visualization.arrayToDataTable([
        ["Mes", "Vuelos"]
        <?php foreach($meses as $m): ?>,
            ["<?= $m['mes'] ?>", <?= $m['total'] ?>]
        <?php endforeach; ?>
    ]);

    let options = {
        title: "Cantidad de vuelos por mes",
        legend: { position: "none" },
        colors: ["#4fa3f7"]
    };

    let chart = new google.visualization.ColumnChart(document.getElementById("chart_mes"));
    chart.draw(data, options);
}
</script>

</head>


<div class="container mt-4">

    <h2>Estadísticas de tus Viajes</h2>

    <p class="text-muted">Ciudades a las que más has viajado</p>
    <div id="chart_destinos" style="width: 100%; height: 500px;"></div>

    <hr class="my-4">

    <p class="text-muted">Vuelos realizados por mes</p>
    <div id="chart_mes" style="width: 100%; height: 500px;"></div>

</div>


