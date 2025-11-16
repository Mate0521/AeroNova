<?php 
if(isset($_POST["registrar"])){
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $telefono = $_POST["telefono"];
    $pasajero = new Pasajero("", $nombre, $apellido, $correo, $telefono, $clave, null);
    $pasajero -> crearPasajero();


}

?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header text-center">
                    <h4 class="mb-0">Registro de Pasajero</h4>
                </div>

                <div class="card-body">
                    <form method="post" action="?pid=<?php echo base64_encode("Registrar") ?>">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" required>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" required>
                        </div>

                        <div class="mb-3">
                            <label for="clave" class="form-label">Clave</label>
                            <input type="password" class="form-control" name="clave" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
