<?php
class AvionDAO
{
    private $matricula;
    private $modelo;
    private $capacidad;

    public function __construct($matricula = null, $modelo = null, $capacidad = null)
    {
        $this->matricula = $matricula;
        $this->modelo = $modelo;
        $this->capacidad = $capacidad;
    }

    public function obtenerAvionMatricula()
    {
        return [
            "sql" => "SELECT `Modelo`, `Capacidad` 
                    FROM `avion` 
                    WHERE `Matricula`= :matricula",
            "parametros" => [
                ":matricula" => $this->matricula
            ]
        ];
    }
}