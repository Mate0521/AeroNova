<?php 
function cargarEnv($ruta) {
    if (!file_exists($ruta)) {
        return;
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {

        // Ignorar comentarios
        if (strpos(trim($linea), '#') === 0) {
            continue;
        }

        // Separar clave=valor
        list($clave, $valor) = explode('=', $linea, 2);

        // Limpiar espacios y comillas
        $clave = trim($clave);
        $valor = trim($valor, " \t\n\r\0\x0B\"'");

        // Guardar en variables globales
        $_ENV[$clave] = $valor;
        putenv("$clave=$valor");
    }
}

// Cargar en el arranque
cargarEnv(__DIR__ . '/../.env');