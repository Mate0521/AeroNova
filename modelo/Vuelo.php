<?php

class Vuelo
{
    private $idVuelo;
    private $fecha;
    private $hora_despegue;
    private $piloto_principal;//ob piloto, consulta cascada
    private $copiloto;//ob piloto, consulta cascada
    private $avion;//ob avion, consulta cascada
    private $ruta;//ob ruta, consulta cascada
    private $hora_llegada;
    private $estado_vuelo;//ob estado, consulta cascada

    // Constructor
    public function __construct($idVuelo = null, $fecha = null, $hora_despegue = null, $piloto_principal = null, $copiloto = null, $avion = null, $ruta = null, $hora_llegada = null, $estado_vuelo = null)
    {
        $this->idVuelo = $idVuelo;
        $this->fecha = $fecha;
        $this->hora_despegue = $hora_despegue;
        $this->piloto_principal = $piloto_principal;
        $this->copiloto = $copiloto;
        $this->avion = $avion;
        $this->ruta = $ruta;
        $this->hora_llegada = $hora_llegada;
        $this->estado_vuelo = $estado_vuelo;
    }
    // Getters
    public function getIdVuelo()
    {
        return $this->idVuelo;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHoraDespegue()
    {
        return $this->hora_despegue;
    }
    public function getPilotoPrincipal()
    {
        return $this->piloto_principal;
    }
    public function getCopiloto()
    {
        return $this->copiloto;
    }
    public function getAvion()
    {
        return $this->avion;
    }
    public function getRuta()
    {
        return $this->ruta;
    }
    public function getHoraLlegada()
    {
        return $this->hora_llegada;
    }
    public function getEstadoVuelo()
    {
        return $this->estado_vuelo;
    }
    // Setters
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setHoraDespegue($hora_despegue)
    {
        $this->hora_despegue = $hora_despegue;
    }
    public function setPilotoPrincipal($piloto_principal)
    {
        $this->piloto_principal = $piloto_principal;
    }
    public function setCopiloto($copiloto)
    {
        $this->copiloto = $copiloto;
    }
    public function setAvion($avion)
    {
        $this->avion = $avion;
    }
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }
    public function setHoraLlegada($hora_llegada)
    {
        $this->hora_llegada = $hora_llegada;
    }
    public function setEstadoVuelo($estado_vuelo)
    {
        $this->estado_vuelo = $estado_vuelo;
    }


    public function obtenerVueloId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO($this->idVuelo, null, null, null, null, null, null);
        try {
            $sql = $vueloDAO->obtenerVueloId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->fecha = $fila[0];
                $this->hora_despegue = $fila[1];

                $pilotoOB = new Piloto($fila[2]);
                $pilotoOB->obtenerPilotoId();
                $this->piloto_principal = $pilotoOB;

                $copilotoOB = new Piloto($fila[3]);
                $copilotoOB->obtenerPilotoId();
                $this->copiloto = $copilotoOB;

                $avionOB = new Avion($fila[4]);
                $avionOB->obtenerAvionMatricula();
                $this->avion = $avionOB;

                $rutaOB = new Ruta($fila[5]);
                $rutaOB->obtenerRutaId();
                $this->ruta = $rutaOB;

                $this->hora_llegada = $fila[6];

                $estadoOB = new Estado($fila[7]);
                $estadoOB->obtenerEstadoVueloId();
                $this->estado_vuelo = $estadoOB;
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
    public function consultarVuelos()//lista de 5 vuelos
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO();
        $vuelos = [];
        try {
            $sql = $vueloDAO->consultarVuelos();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            while ($fila = $conexion->registro()) {
                $vuelo = new Vuelo($fila[0], $fila[1], $fila[2], null, null, null, null, $fila[7], null);

                $pilotoOB = new Piloto($fila[3]);
                $pilotoOB->obtenerPilotoId();
                $vuelo->setPilotoPrincipal($pilotoOB);

                $copilotoOB = new Piloto($fila[4]);
                $copilotoOB->obtenerPilotoId();
                $vuelo->setCopiloto($copilotoOB);

                $avionOB = new Avion($fila[5]);
                $avionOB->obtenerAvionMatricula();
                $vuelo->setAvion($avionOB);

                $rutaOB = new Ruta($fila[6]);
                $rutaOB->obtenerRutaId();
                $vuelo->setRuta($rutaOB);

                $estadoOB = new Estado($fila[8]);
                $estadoOB->obtenerEstadoVueloId();
                $vuelo->setEstadoVuelo($estadoOB);

                $vuelos[] = $vuelo;
            }
            $conexion->cerrar();
            return $vuelos;
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

}