<?php
class Ciudad
{
    private $idCiudad;
    private $nombre;


    //constructor
    public function __construct($id = null, $nombre = null)
    {
        $this->idCiudad = $id;
        $this->nombre = $nombre;

    }
    //getters
    public function getId()
    {
        return $this->idCiudad;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    //setters
    public function setId($id)
    {
        $this->idCiudad = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function obtenerCiudadId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ciudadDAO = new CiudadDAO($this->idCiudad, null);
        try {
            $sql = $ciudadDAO->obtenerCiudadId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->nombre = $fila[0];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
}