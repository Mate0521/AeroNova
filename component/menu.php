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

    <?php if($_SESSION["rol"] == "admin"): ?>
        <!-- Menú Admin -->
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="?pid=<?= base64_encode('Home') ?>">AeroNova</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">AeroNova</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('Home') ?>">Ver Pilotos</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('CrearCamp') ?>">Ver Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('CrearEquipo') ?>">Ver Aviones</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('EliminarCamp') ?>">Ver Vuelos</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> <?= $admin->getNombre() ?>
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelDatos') ?>">Datos Personales</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="index.php" method="post">
                                            <button type="submit" class="btn btn-danger" name="cerrarSesion">Cerrar Sesión</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

    <?php elseif($_SESSION["rol"] == "pasajero"): ?>
        <!-- Menú Pasajero -->
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="?pid=<?= base64_encode('panelPasajero') ?>">AeroNova</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">AeroNova</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('panelPasajero') ?>">Dashboard</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> <?= $pasajero->getNombre()." ".$pasajero->getApellido() ?>
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelDatos') ?>">Datos Personales</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="index.php" method="post">
                                            <button type="submit" class="btn btn-danger" name="cerrarSesion">Cerrar Sesión</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

    <?php elseif($_SESSION["rol"] == "piloto"): ?>
        <!-- Menú Piloto -->
        <nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="?pid=<?= base64_encode('panelPiloto') ?>">AeroNova</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" id="offcanvasNavbar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">AeroNova</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

                    <!-- Panel de vuelos -->
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

                    <!-- Datos personales y cerrar sesión -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= $piloto->getNombre()." ".$piloto->getApellido() ?>
                        </a>
                        <ul class="dropdown-menu text-center">
                            <li>
                                <a class="dropdown-item" href="?pid=<?= base64_encode('PanelDatosPiloto') ?>">
                                    <i class="bi bi-person-lines-fill"></i> Datos Personales
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="index.php" method="post">
                                    <button type="submit" class="btn btn-danger w-100" name="cerrarSesion">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>

    <?php endif; ?>

<?php else: ?>
    <!-- Menú público -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="?pid=<?= base64_encode('Home') ?>">AeroNova</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="?pid=<?= base64_encode('Home') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Check-in</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Ofertas y Destinos</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Vuelos</a></li>
                            <li><a class="dropdown-item" href="#">Rutas</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="?pid=<?= base64_encode('Login') ?>" class="btn btn-outline-secondary">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </nav>
<?php endif; ?>

</header>
