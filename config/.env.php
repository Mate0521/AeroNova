<?php

if (!function_exists("cargarEnv")) {

    function cargarEnv($ruta) {
        if (!file_exists($ruta)) return;

        $lines = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }

    // cargar una sola vez
    cargarEnv(__DIR__ . '/../../.env');
}
