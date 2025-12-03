
<?php
// Verificar sesión y cargar objeto según rol
if(isset($_SESSION["rol"])){
    switch($_SESSION["rol"]){
        case "admin":
            $admin = new Admin($_SESSION["id"]);
            $admin->obtenerAdminId();
            break;
        case "pasajero":
            $pasajero = new Pasajero($_SESSION["id"]);
            $pasajero->obtenerPasajeroId();
            break;
        case "piloto":
            $piloto = new Piloto($_SESSION["id"]);
            $piloto->obtenerPilotoId();
            break;
        default:
            header('Location: index.php');
            exit();
    }
}
?>

<header class="text-center p-3 sticky-top mb-4">
<?php if(isset($_SESSION["rol"])): ?>
    <nav class="navbar navbar-dark bg-black fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?pid=<?= base64_encode('Home') ?>">AeroNova</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end text-white bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AeroNova</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <?php if($_SESSION["rol"] == "admin"): //admin?>

                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode("dashboarAdmin") ?>"><i class="bi bi-box-arrow-in-down-left"></i> Dashboard</a></li>
                            <!-- pilotos -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> Pilotos
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelPilotoAdmin') ?>">Informacion Piloto</a></li>
                                </ul>
                            </li>
                            <!-- Pasajeros -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-bounding-box"></i> Pasajeros
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('administrarPasajeros') ?>">Adminidtrar Pasajeros</a></li>
                                </ul>
                            </li>
                            <!-- Vuelos -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-sign-turn-slight-left"></i> Vuelos
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('SolicitarVuelo') ?>">Asignar vuelos</a></li>
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('solicitudcopiloto') ?>">Asignar copiloto</a></li>
                                </ul>
                            </li>
                            <!-- Aviones -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-airplane-fill"></i> Aviones
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelAviones') ?>">Ver Aviones</a></li>
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('addAvion') ?>">Nuevo avion</a></li>
                                </ul>
                            </li>
                            <!-- Rutas -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-geo-alt"></i> Rutas
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelRutas') ?>">Ver Rutas</a></li>
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode("addRuta") ?>">Crear Nueva Ruta</a></li>
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode("addCiudad") ?>">Crear Nueva Ciudad Aeroportuaria</a></li>
                                </ul>
                            </li>

                            <?php $userName = $admin->getNombre(); 
                            $panel="PanelDatosAdmin"?>

                        <?php elseif($_SESSION["rol"] == "pasajero"): //pasajero?>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode("panelPasajero") ?>"><i class="bi bi-box-arrow-in-down-left"></i> Buscar Vuelos</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('dashboarad') ?>"><i class="bi bi-box-arrow-in-down-left"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode("Checkin") ?>"><i class="bi bi-box-arrow-in-down-left"></i> Check-in</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode("constTick") ?>"><i class="bi bi-box-arrow-in-down-left"></i> Consultar mis tikets</a></li>
                            <?php $userName = $pasajero->getNombre() . " " . $pasajero->getApellido(); 
                            $panel="PanelDatosPasajero" ?>

                        <?php elseif($_SESSION["rol"] == "piloto"): //piloto?>
                            <li class="nav-item">
                                <a class="nav-link" href="?pid=<?= base64_encode('panelVuelos') ?>">
                                    <i class="bi bi-box-arrow-in-down-left"></i> Mis Vuelos
                                </a>
                            </li>

                            <!-- Historial de vuelos -->
                            <li class="nav-item">
                                <a class="nav-link" href="?pid=<?= base64_encode('HistorialVuelosPiloto') ?>">
                                    <i class="bi bi-clock-history"></i> Historial
                                </a>
                            </li>
                            <?php $userName = $piloto->getNombre() . " " . $piloto->getApellido(); 
                            $panel="PanelDatosPiloto"?>
                        <?php endif; ?>

                        <li class="nav-item dropdown mt-3">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= $userName ?>
                            </a>
                            <ul class="dropdown-menu text-center">
                                <li><a class="dropdown-item" href="?pid=<?= base64_encode($panel) ?>">Datos Personales</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="?pid=<?= base64_encode('Home') ?>" method="POST">
                                        <button type="submit" class="btn btn-danger w-100" name="cerrarSecion">Cerrar sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
<?php else: ?>
    <nav class="navbar navbar-dark bg-black fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?pid=<?= base64_encode('Home') ?>">AeroNova</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end text-white bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AeroNova</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('Home') ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode("Checkin") ?>">Check-in</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Informacion sobre vuelos y aviones</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?pid=<?= base64_encode('vuelos') ?>">Vuelos</a></li>
                                <li><a class="dropdown-item" href="?pid=<?= base64_encode("aviones")?>">Aviones</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="d-flex mt-3">
                        <a href="?pid=<?= base64_encode("Login")?>" class="btn btn-outline-secondary w-100">Iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<?php endif; ?>
</header>