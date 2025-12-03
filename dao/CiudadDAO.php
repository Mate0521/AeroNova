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
                    FROM `g2_ciudad` 
                    WHERE `idCiudad`= :idCiudad",
            "parametros" => [
                ":idCiudad" => $this->idCiudad
            ]
        ];
    }
    public function obtenerCiudades()
    {
        return [
            "sql" => "SELECT `idCiudad`, `Nombre` 
                FROM `g2_ciudad`",
            "parametros" => []
        ];
    }

    public function crearCiudad()
    {
        return [
            "sql" => "INSERT INTO `g2_ciudad`( `Nombre`) 
                VALUES (:nombre)",
            "parametros" => [ 
                ":nombre"=>$this->nombre 
            ]
        ];
    }

}