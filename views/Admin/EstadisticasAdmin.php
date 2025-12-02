<?php
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ?pid=" . base64_encode("Error"));
    exit;
}
?>

<div class="container mt-4">

    <h2 class="mb-4">
        <i class="bi bi-bar-chart-fill"></i> Dashboard de Estadísticas
    </h2>


    <div class="row mt-4">

        <!-- 1. Pasajeros por estado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow p-3">
                <h5>Pasajeros por Estado</h5>
                <div id="chart_pax_estado" style="height: 320px;">
                    <div class="spinner-border"></div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-outline-primary btn-sm reload-chart" data-chart="paxEstado">Recargar</button>
                    <button class="btn btn-outline-danger btn-sm export-pdf" data-chart="paxEstado">PDF</button>
                </div>
            </div>
        </div>


        <!-- 2. Tickets por estado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow p-3">
                <h5>Tickets por Estado</h5>
                <div id="chart_tickets_estado" style="height: 320px;">
                    <div class="spinner-border"></div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-outline-primary btn-sm reload-chart" data-chart="ticketEstado">Recargar</button>
                    <button class="btn btn-outline-danger btn-sm export-pdf" data-chart="ticketEstado">PDF</button>
                </div>
            </div>
        </div>


        <!-- 3. Vuelos por estado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow p-3">
                <h5>Vuelos por Estado</h5>
                <div id="chart_vuelos_estado" style="height: 320px;">
                    <div class="spinner-border"></div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-outline-primary btn-sm reload-chart" data-chart="vuelosEstado">Recargar</button>
                    <button class="btn btn-outline-danger btn-sm export-pdf" data-chart="vuelosEstado">PDF</button>
                </div>
            </div>
        </div>


        <!-- 4. Vuelos programados por mes -->
        <div class="col-md-6 mb-4">
            <div class="card shadow p-3">
                <h5>Vuelos Programados por Mes</h5>
                <div id="chart_vuelos_mes" style="height: 320px;">
                    <div class="spinner-border"></div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-outline-primary btn-sm reload-chart" data-chart="vuelosMes">Recargar</button>
                    <button class="btn btn-outline-danger btn-sm export-pdf" data-chart="vuelosMes">PDF</button>
                </div>
            </div>
        </div>


        <!-- 5. Rutas más populares -->
        <div class="col">
            <div class="card shadow p-3">
                <h5>Rutas más Populares (Top 5)</h5>
                <div id="chart_rutas_populares" >
                    <div class="spinner-border"></div>
                </div>

                <div class="mt-2">
                    <button class="btn btn-outline-primary btn-sm reload-chart" data-chart="rutasTop">Recargar</button>
                    <button class="btn btn-outline-danger btn-sm export-pdf" data-chart="rutasTop">PDF</button>
                </div>
            </div>
        </div>

    </div>

</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    google.charts.load("current", { packages:["corechart"] });

    $(document).ready(function() {
        cargarGraficoPasajerosEstado();
        cargarGraficoTicketsEstado();
        cargarGraficoVuelosEstado();
        cargarVuelosPorMes();
        cargarRutasPopulares();
    });

    $(document).on("click", ".reload-chart", function () {
        let chart = $(this).data("chart");

        switch(chart) {
            case "paxEstado":
                cargarGraficoPasajerosEstado();
                break;

            case "ticketEstado":
                cargarGraficoTicketsEstado();
                break;

            case "vuelosEstado":
                cargarGraficoVuelosEstado();
                break;

            case "vuelosMes":
                cargarVuelosPorMes();
                break;

            case "rutasTop":
                cargarRutasPopulares();
                break;

            default:
                console.error("Gráfico no reconocido:", chart);
        }
    });


    function cargarGraficoPasajerosEstado() {

        $("#chart_pax_estado").html("<div class='text-center p-5'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: "ajax/PasajerosEstado.php",
            type: "GET",
            dataType: "json",
            success: function(data) {

                google.charts.setOnLoadCallback(function(){

                    let tabla = google.visualization.arrayToDataTable([
                        ['Estado', 'Cantidad'],
                        ['Activos', data.activos],
                        ['Inactivos', data.inactivos]
                    ]);

                    let opciones = {
                        title: 'Estado de Cuenta de Pasajeros',
                        pieHole: 0.4,
                        colors: ['#28a745', '#dc3545']
                    };

                    let chart = new google.visualization.PieChart(document.getElementById('chart_pax_estado'));
                    chart.draw(tabla, opciones);
                });
            }
        });
    }

    function cargarGraficoTicketsEstado() {

        $("#chart_tickets_estado").html("<div class='text-center p-5'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: "ajax/TikcketEstado.php",
            type: "GET",
            dataType: "json",
            success: function(data) {

                google.charts.setOnLoadCallback(function(){

                    let tabla = google.visualization.arrayToDataTable([
                        ['Estado', 'Cantidad', { role: 'style' }],
                        ['Reservado', data.reservado, '#007bff'],
                        ['Pagado', data.pagado, '#28a745'],
                        ['Cancelado', data.cancelado, '#dc3545'],
                        ['Check-in', data.checkin, '#ffc107']
                    ]);

                    let opciones = {
                        title: 'Estado de Tickets',
                        legend: { position: 'none' }
                    };

                    let chart = new google.visualization.ColumnChart(
                        document.getElementById('chart_tickets_estado')
                    );

                    chart.draw(tabla, opciones);
                });
            }
        });
    }

    function cargarGraficoVuelosEstado() {

        $("#chart_vuelos_estado").html("<div class='text-center p-5'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: "ajax/vuelosEstado.php",
            type: "GET",
            dataType: "json",
            success: function(data) {

                google.charts.setOnLoadCallback(function(){

                    let tabla = google.visualization.arrayToDataTable([
                        ['Estado', 'Cantidad', { role: 'style' }],
                        ['Programado', data.programado, '#0d6efd'],
                        ['En vuelo', data.envuelo, '#198754'],
                        ['Aterrizado', data.aterrizado, '#0dcaf0'],
                        ['Retrasado', data.retrasado, '#ffc107'],
                        ['Cancelado', data.cancelado, '#dc3545'],
                        ['Solicitado', data.solicitado, '#6c757d'],
                        ['Rechazado', data.rechazado, '#6610f2']
                    ]);

                    let opciones = {
                        title: 'Estado de los Vuelos',
                        legend: { position: 'none' }
                    };

                    let chart = new google.visualization.ColumnChart(
                        document.getElementById('chart_vuelos_estado')
                    );

                    chart.draw(tabla, opciones);
                });
            }
        });
    }

    function cargarVuelosPorMes() {

        $("#chart_vuelos_mes").html("<div class='text-center p-5'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: "ajax/VuelosMes.php",
            type: "GET",
            dataType: "json",
            success: function(data) {

                google.charts.setOnLoadCallback(() => {

                    let tabla = new google.visualization.DataTable();
                    tabla.addColumn('string', 'Mes');
                    tabla.addColumn('number', 'Vuelos programados');

                    let meses = [
                        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
                        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
                    ];

                    for (let i = 0; i < 12; i++) {
                        tabla.addRow([meses[i], data[i+1]]);
                    }

                    let opciones = {
                        title: "Vuelos Programados por Mes",
                        hAxis: { title: "Meses" },
                        vAxis: { title: "Cantidad" },
                        legend: 'none'
                    };

                    let chart = new google.visualization.ColumnChart(
                        document.getElementById('chart_vuelos_mes')
                    );

                    chart.draw(tabla, opciones);
                });
            }
        });
    }

    function cargarRutasPopulares() {

        $("#chart_rutas_populares").html("<div class='text-center p-5'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: "ajax/RutasPopulares.php",
            type: "GET",
            dataType: "json",
            success: function (data) {

                google.charts.setOnLoadCallback(() => {

                    let tabla = new google.visualization.DataTable();
                    tabla.addColumn('string', 'Ruta');
                    tabla.addColumn('number', 'Tickets Vendidos');

                    data.forEach(r => {
                        tabla.addRow([r.ruta, parseInt(r.total)]);
                    });

                    let opciones = {
                        title: "Top 5 Rutas con más Tickets Vendidos",
                        legend: { position: "none" },
                        bars: "horizontal",
                        height: 400
                    };

                    let chart = new google.visualization.BarChart(
                        document.getElementById("chart_rutas_populares")
                    );

                    chart.draw(tabla, opciones);
                });
            }
        });
    }

    $(document).on("click", ".export-pdf", function () {

        let chartType = $(this).data("chart");
        let divId = "";

        switch(chartType) {
            case "paxEstado":     divId = "chart_pax_estado"; break;
            case "ticketEstado":  divId = "chart_tickets_estado"; break;
            case "vuelosEstado":  divId = "chart_vuelos_estado"; break;
            case "vuelosMes":     divId = "chart_vuelos_mes"; break;
            case "rutasTop":      divId = "chart_rutas_populares"; break;
            default:
                alert("Gráfico no reconocido");
                return;
        }

        let chartElement = document.getElementById(divId);
        let svg = chartElement.getElementsByTagName("svg")[0];

        if (!svg) {
            alert("Debes cargar el gráfico antes de exportarlo.");
            return;
        }

        // Convertimos SVG -> PNG
        let serializer = new XMLSerializer();
        let svgString = serializer.serializeToString(svg);
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext("2d");

        let img = new Image();
        img.onload = function () {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            let pngData = canvas.toDataURL("image/png");

            // Enviar PNG por AJAX a PHP
            $.ajax({
                url: "ajax/exportPDF.php",
                type: "POST",
                data: { img: pngData, name: chartType },
                xhrFields: { responseType: 'blob' },
                success: function(blob) {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement("a");
                    a.href = url;
                    a.download = chartType + "_grafico.pdf";
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                }
            });
        };

        img.src = "data:image/svg+xml;base64," + btoa(svgString);
    });



</script>

