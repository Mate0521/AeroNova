    <div class="card text-center shadow-lg border-0 p-4 col-10 col-md-6 col-lg-4">
        <div class="mb-3">
            <i class="bi bi-shield-lock display-1 text-danger"></i>
        </div>
        <h3 class="text-danger fw-bold">Acceso Denegado</h3>
        <p class="text-muted mb-4">
            No tienes permiso para acceder a esta página o la solicitud es inválida.
        </p>
        <a href="?pid=<?php echo base64_encode("Home")?>" class="btn btn-outline-primary">
            <i class="bi bi-house-door"></i> Volver al inicio
        </a>
    </div>