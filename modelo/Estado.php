<?php
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
        } else {
            $this->valor = "Sin Estado"; 
        }
        $conexion->cerrar();
    } catch (Exception $e) {
        $conexion->cerrar();
        $this->valor = "Error";
        return $e;
    }
}
public function obtenerEstadoVuelo()
{
    $conexion = new Conexion();
    $conexion->abrir();
    $estadoDAO = new EstadoDAO(); // sin id, porque queremos todos

    try {
        // Obtenemos la consulta que trae todos los estados
        $sql = $estadoDAO->obtenerEstadoVuelo(); 
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        $estados = [];
        while ($fila = $conexion->registro()) {
            $estados[] = [
                "id_estado" => $fila["id_estado"],
                "nombre_estado" => $fila["Valor"]
            ];
        }

        $conexion->cerrar();
        return $estados;

    } catch (Exception $e) {
        $conexion->cerrar();
        return [];
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
    public function obtenerEstadoPilotoId() {
        $conexion = new Conexion();
        $conexion->abrir();
        $estadoDAO = new EstadoDAO($this->idEstado);

        try {
            $sql = $estadoDAO->obtenerEstadoPilotoId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            if ($fila = $conexion->registro()) {
                $this->valor = $fila[0]; // asigna el valor desde la BD
            }

            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
  public function obtenerEstadoPilotos() {
        $conexion = new Conexion();
        $conexion->abrir();
        $estadoDAO = new EstadoDAO();

        try {
            $sql = $estadoDAO->obtenerEstadoPilotoS(); // ← usa el método correcto
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            $estados = [];

            while ($fila = $conexion->registro()) {
                $e = new Estado();
                $e->setIdEstado($fila[0]);
                $e->setValor($fila[1]);
                $estados[] = $e;
            }

            $conexion->cerrar();
            return $estados;

        } catch (Exception $e) {
            $conexion->cerrar();
            return [];
        }
    
    }
}