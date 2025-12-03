<?php

class RutaDAO
{
    private $idRuta;
    private $duracion_estimada;
    private $distancia_KM;
    private $origen;
    private $destino;

    public function __construct($idRuta = null, $duracion_estimada = null, $distancia_KM = null, $origen = null, $destino = null)
    {
        $this->idRuta = $idRuta;
        $this->duracion_estimada = $duracion_estimada;
        $this->distancia_KM = $distancia_KM;
        $this->origen = $origen;
        $this->destino = $destino;
    }

    public function obtenerRutaId()
    {
        return [
            "sql" => "SELECT `Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino` 
                    FROM `g2_ruta` 
                    WHERE `idRuta`= :idRuta",
            "parametros" => [
                ":idRuta" => $this->idRuta
            ]
        ];
    }

    public function obtenerRutas()
    {
        return [
            "sql" => "SELECT `idRuta`,`Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino` 
                    FROM `g2_ruta` ",
            "parametros" => []
        ];
    }
}