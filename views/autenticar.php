<?php
require_once(__DIR__ . '/../modelo/Admin.php');
require_once(__DIR__ . '/../dao/AdminDAO.php');

require_once(__DIR__ . '/../modelo/Persona.php');
require_once(__DIR__ . '/../modelo/Pasajero.php');
require_once(__DIR__ . '/../modelo/Piloto.php');




// mostrar errores en desarrollo (quita en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = 0;

if (isset($_POST["autenticar"])) {
    $correo = $_POST["correo"] ?? '';
    $clave  = $_POST["clave"] ?? '';

    $admin = new Admin("", "", "", $correo, "",$clave);
    $piloto = new Piloto("", "", "", $correo, "",$clave);
    $pasajero = new Pasajero("", "", "", $correo, "",$clave);
    if ($admin->autenticar()) {
        $_SESSION["id"] = $admin->getId();
        $_SESSION["rol"] = "admin";
        $_SESSION["mensaje"] = "¡Credenciales correctas!";
        header('Location: ?pid='. base64_encode('panelAdmin'));
        exit();
    } else if($piloto->autenticar()){
        $_SESSION["id"] = $piloto->getId();
        $_SESSION["rol"] = "piloto";
        $_SESSION["mensaje"] = "¡Credenciales correctas!";
        header('Location: /?pid='. base64_encode('panelPiloto'));
        exit();

    } else if($pasajero->autenticar()){
        $_SESSION["id"] = $pasajero->getId();
        $_SESSION["rol"] = "pasajero";
        $_SESSION["mensaje"] = "¡Credenciales correctas!";
        header('Location: /?pid='. base64_encode('panelPasajero'));
        exit();

    } 
    
    else {
        $error = 1;
    }
}
?>

<div class="bg-light d-flex justify-content-center align-items-center vh-100">
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $error = 0;

    if (isset($_POST["autenticar"])) {
        $correo = $_POST["correo"] ?? '';
        $clave  = $_POST["clave"] ?? '';

        $admin = new Admin("", "", "", $correo, "", $clave);

        if ($admin->autenticar()) {
            $_SESSION["id"] = $admin->getId();
            $_SESSION["rol"] = "admin";
            $_SESSION["mensaje"] = "¡Credenciales correctas!";
            header('Location: ?pid=' . base64_encode('panelAdmin'));
            exit();
        } else {
            $error = 1;
        }
    }
    ?>

    <div class="card shadow-lg p-4" style="max-width: 380px; width: 100%;">
        <div class="text-center mb-3">
            <div class="display-4">✈️</div>
            <h3 class="fw-bold text-primary">AeroNova</h3>
            <p class="text-muted">Control de acceso</p>
        </div>

        <?php if ($error == 1): ?>
            <div class="alert alert-danger text-center">
                Correo o clave incorrectos
            </div>
        <?php endif; ?>

        <form method="post" action="?pid=<?php echo base64_encode('Login'); ?>">

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" class="form-control form-control-lg" name="correo" placeholder="Ingresa tu correo" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Clave</label>
                <input type="password" class="form-control form-control-lg" name="clave" placeholder="Ingresa tu clave" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100" name="autenticar">
                Iniciar sesión
            </button>
        </form>
    </div>

</div>

