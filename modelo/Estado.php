<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/EstadoDAO.php');
class Estado
{
    private $idEstado;
    private $valor;
    //constructor
    public function __construct($idEstado = null, $valor = null)
    {
        $this->idEstado = $idEstado;
        $this->valor = $valor;
    }
    //getters
    public function getIdEstado()
    {
        return $this->idEstado;
    }
    public function getValor()
    {
        return $this->valor;
    }
    //setters
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;
    }
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function obtenerEstadoVueloId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $estadoDAO = new EstadoDAO($this->idEstado, null);
        try {
            $sql = $estadoDAO->obtenerEstadoVueloId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->valor = $fila[0];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
    public function obtenerEstadoTicketId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $estadoDAO = new EstadoDAO($this->idEstado, null);
        try {
            $sql = $estadoDAO->obtenerEstadoTicketId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->valor = $fila[0];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
    public function obtenerEstadoPilotoId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $estadoDAO = new EstadoDAO($this->idEstado, null);
        try {
            $sql = $estadoDAO->obtenerEstadoPilotoId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->valor = $fila[0];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
}