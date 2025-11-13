<?php

function limpiarEntrada($dato)
{
    if (!isset($dato)) {
        return null;
    }

    $dato = (string)$dato;

    $dato = trim($dato);

    $dato = strip_tags($dato);

    $dato = str_replace(
        ['"', "'", '`', '´', '‘', '’', '“', '”'],
        '',
        $dato
    );

    $dato = preg_replace('/[\x00-\x1F\x7F]/u', '', $dato);

    $dato = htmlspecialchars($dato, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    

    $dato = preg_replace('/\s+/', ' ', $dato);

    return $dato;
}
