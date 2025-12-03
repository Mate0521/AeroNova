<?php
require_once (__DIR__."/../config/env.php");
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/AvionDAO.php');


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
<<<<<<< HEAD

    public function consultar()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $avionDAO = new AvionDAO();
        try {
            $sql = $avionDAO->consultar();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $aviones = [];
            while ($fila = $conexion->registro()) {
                $a = new Avion($fila[0],$fila[1],$fila[2]);
                $aviones[]=$a;
            }
            $conexion->cerrar();
            return $aviones;
=======
    
public function obtenerAviones() {
    $conexion = new Conexion();
        $conexion->abrir();
        $avionDAO = new AvionDAO();
        $aviones = [];

        try {
            $sql = $avionDAO->obtenerAviones();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            while ($fila = $conexion->registro()) {
                $avion = new Avion($fila[0], $fila[1], $fila[2]);
                $aviones[] = $avion;
            }

            $conexion->cerrar();
            return $aviones;

>>>>>>> feature/Natalia
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
<<<<<<< HEAD
    }

    public function actualizarCampos($cambios)
    {
        $conexion = new Conexion();
        $conexion->abrir();

        try {

            $avionDAO = new AvionDAO(
                $this->matricula,
                isset($cambios["modelo"]) ? $cambios["modelo"] : null,
                isset($cambios["capacidad"]) ? $cambios["capacidad"] : null
            );

            foreach ($cambios as $campo => $valorNuevo) {

                switch ($campo) {

                    case "modelo":
                        $sql = $avionDAO->actualizarModelo();
                        break;

                    case "capacidad":
                        $sql = $avionDAO->actualizarCapacidad();
                        break;
                }

                $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            }

            $conexion->cerrar();
            return "ok";

        } catch (Exception $e) {
            $conexion->cerrar();
            return "error".$e;
        }
    }

    public function obtenerHistorialVuelos()
    {
        $conexion = new Conexion();
        $conexion->abrir();

        $avionDAO = new AvionDAO($this->matricula);
        
        try {
            $sql = $avionDAO->obtenerHistorialVuelos();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            $vuelos = [];
            
            while ($fila = $conexion->registro()) {

                $ciudadOrigen = new Ciudad($fila[4], $fila[5]);
                $ciudadDestino = new Ciudad($fila[6], $fila[7]);
                $ruta = new Ruta(null, null, null,
                $ciudadOrigen, $ciudadDestino);

                $estadoVuelo = new Estado($fila[8], $fila[9]);

                $vuelo = new Vuelo($fila[0],$fila[1],$fila[2],null,null,null,
                    $ruta,$fila[3],$estadoVuelo
                );

                $vuelos[] = $vuelo;
            }

            $conexion->cerrar();
            return $vuelos;

        } catch (Exception $e) {
            $conexion->cerrar();
            return [];
        }
    }

    public function obtenerSugerenciasModelos($texto)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $avionDAO = new AvionDAO();
        try {
            $sql = $avionDAO->buscarModelos($texto);
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $lista = [];

            while ($fila = $conexion->registro()) {
                $lista[] = $fila[0];
            }

            $conexion->cerrar();
            return $lista;

        } catch (Exception $e) {
            $conexion->cerrar();
            return [];
        }
    }

    public function crearAvion()
    {
        $conexion = new Conexion();
        $conexion->abrir();

        try {
            $avionDAO = new AvionDAO($this->matricula, $this->modelo, $this->capacidad);

            $sql = $avionDAO->crear();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            $conexion->cerrar();
            return "ok";

        } catch (Exception $e) {

            $conexion->cerrar();
            return $e->getMessage();
        }
    }

    public function buscarAvion($texto)
    {
        $conexion = new Conexion();
        $conexion->abrir();

        $avionDAO = new AvionDAO();
        $sql = $avionDAO->buscarAvion($texto);

        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        $lista = [];

        while ($fila = $conexion->registro()) {

            $p = new Avion( $fila[0],$fila[1],$fila[2]);

            $lista[] = $p;
        }

        $conexion->cerrar();
        return $lista;
    }




=======
}
>>>>>>> feature/Natalia
}
