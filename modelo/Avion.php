<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/AvionDAO.php');
require_once (__DIR__."/../config/.env.php");

class Avion
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

    public function getMatricula() { return $this->matricula; }
    public function getModelo() { return $this->modelo; }
    public function getCapacidad() { return $this->capacidad; }

    public function setMatricula($matricula) { $this->matricula = $matricula; }
    public function setModelo($modelo) { $this->modelo = $modelo; }
    public function setCapacidad($capacidad) { $this->capacidad = $capacidad; }

    public function obtenerAvionMatricula()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $avionDAO = new AvionDAO($this->matricula, null, null);
        try {
            $sql = $avionDAO->obtenerAvionMatricula();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->modelo = $fila[0];
                $this->capacidad = $fila[1];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
}
