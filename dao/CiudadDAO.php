<?php
class CiudadDAO
{
    private $idCiudad;
    private $nombre;

    public function __construct($idCiudad = null, $nombre = null)
    {
        $this->idCiudad = $idCiudad;
        $this->nombre = $nombre;
    }

    public function obtenerCiudadId()
    {
        return [
            "sql" => "SELECT `Nombre` 
                    FROM `ciudad` 
                    WHERE `idCiudad`= :idCiudad",
            "parametros" => [
                ":idCiudad" => $this->idCiudad
            ]
        ];
    }
}