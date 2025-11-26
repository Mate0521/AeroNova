<?php
if(isset($_SESSION["rol"])){
    if($_SESSION["rol"] == "pasajero"){ 
        $pasajero = new Pasajero($_SESSION["id"]);
        $pasajero->obtenerPasajeroId();
    } elseif($_SESSION["rol"] == "admin"){
        $admin = new Admin($_SESSION["id"]);
        $admin->obtenerAdminId();
    } elseif($_SESSION["rol"] == "piloto"){
        $piloto = new Piloto($_SESSION["id"]);
        $piloto->obtenerPilotoId();
    } else {
        header('Location: index.php?pid=' . base64_encode("Error"));
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
                        <?php if($_SESSION["rol"] == "admin"): ?>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('Home') ?>"><i class="bi bi-box-arrow-in-down-left"></i> Ver pilotos</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('CrearCamp') ?>"><i class="bi bi-database-add"></i> Ver usuarios</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('CrearEquipo') ?>">Ver aviones</a></li>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('EliminarCamp') ?>"><i class="bi bi-trash3"></i> Ver vuelos</a></li>
                            <?php $userName = $admin->getNombre(); ?>
                        <?php elseif($_SESSION["rol"] == "pasajero"): ?>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('panelAdmin') ?>"><i class="bi bi-box-arrow-in-down-left"></i> Dashboard</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-sign-turn-slight-left"></i> Rutas
                                </a>
                                <ul class="dropdown-menu text-center">
                                    <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelDatos') ?>">Crear Nueva Ruta</a></li>
                                </ul>
                            </li>
                            <?php $userName = $pasajero->getNombre() . " " . $pasajero->getApellido(); ?>
                        <?php elseif($_SESSION["rol"] == "piloto"): ?>
                            <li class="nav-item"><a class="nav-link" href="?pid=<?= base64_encode('Home') ?>"><i class="bi bi-box-arrow-in-down-left"></i> Mis vuelos</a></li>
                            <?php $userName = $piloto->getNombre() . " " . $piloto->getApellido(); ?>
                        <?php endif; ?>

                        <li class="nav-item dropdown mt-3">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= $userName ?>
                            </a>
                            <ul class="dropdown-menu text-center">
                                <li><a class="dropdown-item" href="?pid=<?= base64_encode('PanelDatos') ?>">Datos Personales</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="?pid=<?= base64_encode('Home') ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Check-in</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Ofertas y Destinos</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Vuelos</a></li>
                                <li><a class="dropdown-item" href="#">Rutas</a></li>
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
