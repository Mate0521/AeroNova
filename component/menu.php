<?php
if(isset($_SESSION["role"])){
    if($_SESSION["role"] == "U"){ 
        $pasajero =new Pasajero($_SESSION["id"]);
        $pasajero -> obtenerPasajeroId();
    } elseif($_SESSION["role"] == "A"){
        $admin =new Admin($_SESSION["id"]);
        $admin -> obtenerAdminId();
    }elseif($_SESSION["role"] == "P"){
        $piloto =new Piloto($_SESSION["id"]);
        $piloto -> obtenerPilotoId();
    }else{
        $_GET["pid"]=base64_encode("Error");
        header('Location: index.php');
        exit();
    }
}

?>

<header class="text-center p-3 sticky-top mb-4">
    <?php 
    if(isset($_SESSION["role"])){
        if($_SESSION["role"] == "A"){
    ?>
        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="?pid=<?php echo base64_encode('Home'); ?>">AeroNova</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">AeroNova</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="?pid=<?php echo base64_encode('Home'); ?>"><i class="bi bi-box-arrow-in-down-left"></i> ver pilotos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?pid=<?php echo base64_encode("CrearCamp")?>"><i class="bi bi-database-add"></i>ver usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="?pid=<?php echo base64_encode("CrearEquipo")?>">ver aviones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?pid=<?php echo base64_encode("EliminarCamp")?>"><i class="bi bi-trash3"></i>ver vuelos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="?pid=<?php echo base64_encode("Home")?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            <?php
                                echo $admin->getNombre();
                            ?>
                            </a>
                            <ul class="dropdown-menu text-center">
                                <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("PanelDatos")?>">Datos Personales</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action=<?php echo base64_encode("index.php") ?> method="post">
                                        <button type="submit" class="btn btn-danger" name="cerrarSecion">Cerrar secion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
        </nav>

    <?php }elseif($_SESSION["role"] == "U"){?>

        <nav class="navbar bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Liga Main</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Liga Main</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="?pid=<?php echo base64_encode('Home'); ?>"><i class="bi bi-box-arrow-in-down-left"></i> Mis Vuelos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?pid=<?php echo base64_encode("CrearCamp")?>"><i class="bi bi-database-add"></i>Ofertas y Destinos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="?pid=<?php echo base64_encode("CrearEquipo")?>">Check-in</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="?pid=<?php echo base64_encode("Home")?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            <?php
                                echo $pasajero->getNombre();
                            ?>
                            </a>
                            <ul class="dropdown-menu text-center">
                                <li><a class="dropdown-item" href="?pid=<?php echo base64_encode("PanelDatos")?>">Datos Personales</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action=<?php echo base64_encode("index.php") ?> method="post">
                                        <button type="submit" class="btn btn-danger" name="cerrarSecion">Cerrar secion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
        </nav>

    <?php 
    } }else{}
    ?>
</header>
