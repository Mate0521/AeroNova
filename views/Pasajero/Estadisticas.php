<?php
$googleKey = $_ENV["GOOGLE_MAPS_KEY"];
?>

<head>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load("current", {
    packages: ["geochart", "corechart"],
    mapsApiKey: "<?= $googleKey ?>"
});

google.charts.setOnLoadCallback(() => {
    cargarGeoChart();
    cargarChartMes();
});
</script>
</head>

<div class="container mt-4">

    <h2 class="mb-4">Estadísticas de tus Viajes</h2>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Ciudades a las que más has viajado</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Región:</label>
                <select id="selectRegion" class="form-select">
                    <option value="world">🌍 Mundo</option>

                    <option value="002">🌍 África (continente)</option>
                    <option value="015">África del Norte</option>
                    <option value="011">África Occidental</option>
                    <option value="014">África Oriental</option>
                    <option value="017">África Central</option>
                    <option value="018">África del Sur</option>

                    <option value="150">🌍 Europa (continente)</option>
                    <option value="154">Europa del Norte</option>
                    <option value="155">Europa Occidental</option>
                    <option value="151">Europa del Este</option>
                    <option value="039">Europa del Sur</option>

                    <option value="019">🌍 América (continente)</option>
                    <option value="005">América del Sur</option>
                    <option value="013">América Central</option>
                    <option value="021">América del Norte</option>
                    <option value="029">Caribe</option>

                    <option value="142">🌍 Asia (continente)</option>
                    <option value="034">Asia del Sur</option>
                    <option value="030">Asia Oriental</option>
                    <option value="035">Sudeste Asiático</option>
                    <option value="143">Asia Central</option>
                    <option value="145">Asia Occidental</option>

                    <option value="009">🌍 Oceanía (continente)</option>
                </select>
            </div>
            <div id="chart_destinos" ></div>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-header">
            <h5>Vuelos realizados por mes</h5>
        </div>
        <div class="card-body">
            <div id="chart_mes" ></div>
        </div>
    </div>

</div>

<script>
function cargarGeoChart() {

    $.ajax({
        url: "ajax/estadisticasDestinosPas.php",
        type: "GET",
        dataType: "json",
        success: function(data) {

            let tabla = [["City","Viajes"]];
            data.forEach(row => tabla.push([row.ciudad, parseInt(row.cantidad)]));

            let dataTable = google.visualization.arrayToDataTable(tabla);

            let regionSeleccionada = $("#selectRegion").val();

            let options = {
                region: regionSeleccionada,
                displayMode: "markers",
                colorAxis: { colors: ["lightblue", "blue"] }
            };

            let chart = new google.visualization.GeoChart(
                document.getElementById("chart_destinos")
            );

            chart.draw(dataTable, options);
        }
    });
}
$(document).on("change", "#selectRegion", function() {
    cargarGeoChart();
});


function cargarChartMes() {

    $.ajax({
        url: "ajax/estadisticasMesPas.php",
        type: "GET",
        dataType: "json",
        success: function(data) {

            const nombresMes = {
                1: "Enero", 2: "Febrero", 3: "Marzo",
                4: "Abril", 5: "Mayo", 6: "Junio",
                7: "Julio", 8: "Agosto", 9: "Septiembre",
                10: "Octubre", 11: "Noviembre", 12: "Diciembre"
            };

            let tabla = [["Mes", "Vuelos"]];
            data.forEach(r => {
                tabla.push([nombresMes[r.mes], parseInt(r.total)]);
            });

            let dataTable = google.visualization.arrayToDataTable(tabla);

            let options = {
                title: "Vuelos realizados por mes",
                legend: { position: "none" },
                colors: ["#4fa3f7"]
            };

            let chart = new google.visualization.ColumnChart(
                document.getElementById("chart_mes")
            );

            chart.draw(dataTable, options);
        }
    });
}

</script>
