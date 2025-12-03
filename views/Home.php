<?php
$vuelo = new Vuelo();
$vuelos = $vuelo->consultarVuelos();
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">Lista de Vuelos</h2>

            <div class="row g-4">
                <?php foreach ($vuelos as $vuelo): ?>
                <div class="col-md-4">
                    <div class="card bg-dark text-light border border-white h-100">
                        <div class="card-body">

                            <h5 class="card-title text-info fw-bold">
                                Vuelo: <?php echo $vuelo->getAvion()->getMatricula(); ?>
                            </h5>

                            <p class="mb-1"><strong class="text-white">Fecha:</strong> 
                                <?php echo $vuelo->getFecha(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Hora Despegue:</strong> 
                                <?php echo $vuelo->getHoraDespegue(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Hora Llegada:</strong> 
                                <?php echo $vuelo->getHoraLlegada(); ?>
                            </p>

                            <hr class="border-white">

                            <p class="mb-1"><strong class="text-info">Piloto:</strong> 
                                <?php echo $vuelo->getPilotoPrincipal()->getNombre() . ' ' . $vuelo->getPilotoPrincipal()->getApellido(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-info">Copiloto:</strong> 
                                <?php echo $vuelo->getCopiloto()->getNombre() . ' ' . $vuelo->getCopiloto()->getApellido(); ?>
                            </p>

                            <hr class="border-white">

                            <p class="mb-1"><strong class="text-white">Origen:</strong> 
                                <?php echo $vuelo->getRuta()->getOrigen()->getNombre(); ?>
                            </p>
                            <p class="mb-1"><strong class="text-white">Destino:</strong> 
                                <?php echo $vuelo->getRuta()->getDestino()->getNombre(); ?>
                            </p>

                            <p class="mt-3">
                                <span class="badge bg-info text-dark">
                                    <?php echo $vuelo->getEstadoVuelo()->getValor(); ?>
                                </span>
                            </p>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aeropuerto Virtual</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- ESTILO PERSONALIZADO BONITO -->
  <style>
    body {
        background: linear-gradient(to bottom right, #0a2a43, #00111c);
        min-height: 100vh;
        color: #fff;
        font-family: "Segoe UI", sans-serif;
    }

    /* HERO GLASSMORPHISM */
    .hero-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
    }

    /* CARTAS DE VUELOS */
    .vuelo-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 15px;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.35);
        transition: transform .2s ease, box-shadow .2s ease;
        opacity: 0;
        transform: translateY(20px);
        animation: aparecer .5s forwards;
    }

    .vuelo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(255,255,255,0.25);
    }

    @keyframes aparecer {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* TÍTULOS */
    h1, h2 {
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* HORA DIGITAL */
    #horaActual {
        font-family: "DS-Digital", "Courier New", monospace;
        font-size: 1.5rem;
        color: #00eaff;
        text-shadow: 0 0 8px #00eaff;
        letter-spacing: 4px;
    }

    /* LOADER */
    .loader {
        filter: drop-shadow(0 0 5px #00eaff);
    }
  </style>
</head>

<body>

<!-- ========================= RELOJ SUPERIOR ========================= -->
<div class="container-fluid py-2">
    <div class="d-flex justify-content-end">
        <div id="horaActual"></div>
    </div>
</div>

<!-- ========================= HERO ========================= -->
<div class="container py-4">
    <div class="hero-card text-center mx-auto col-lg-8 col-md-10">
        <h1 class="mb-3">🌐 Aeropuerto Virtual</h1>
        <p class="lead">Consulta vuelos en tiempo real con estilo premium ✨</p>
    </div>
</div>

<!-- ========================= VUELOS ========================= -->
<div class="container mt-4 pb-5">
    <h2 class="text-center mb-4">✈ Vuelos Disponibles</h2>

    <!-- Loader -->
    <div id="loader" class="text-center mb-4 d-none">
        <div class="spinner-border text-info loader" role="status"></div>
    </div>

    <div class="row g-4" id="vuelos"></div>
</div>

<!-- ========================= JS ========================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    /* Reloj digital */
    function actualizarHora() {
        const ahora = new Date();
        document.getElementById("horaActual").innerText =
            ahora.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
    setInterval(actualizarHora, 1000);
    actualizarHora();

    let pagina = 1;
    let cargando = false, fin = false;

    function cargarVuelos() {
        if (cargando || fin) return;
        cargando = true;

        $("#loader").removeClass("d-none");

        $.ajax({
            url: "ajax/cargarVuelos.php",
            type: "POST",
            data: { pag: pagina },
            success: function(html) {
                if ($.trim(html) === "") {
                    fin = true;
                    $("#loader").addClass("d-none");
                    return;
                }
                $("#vuelos").append(html);
                pagina++;
                cargando = false;
                $("#loader").addClass("d-none");
            }
        });
    }

    cargarVuelos();

    /* Scroll infinito */
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
            cargarVuelos();
        }
    });
</script>

</body>
</html>