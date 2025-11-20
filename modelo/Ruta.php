<?php
class Ruta
{
    private $idRuta;
    private $duracion_estimada;
    private $distancia_KM;
    private $origen;//ob ciudad, consulta cascada
    private $destino;//ob ciudad, consulta cascada

    public function __construct($idRuta = null, $duracion_estimada = null, $distancia_KM = null, $origen = null, $destino = null)
    {
        $this->idRuta = $idRuta;
        $this->duracion_estimada = $duracion_estimada;
        $this->distancia_KM = $distancia_KM;
        $this->origen = $origen;
        $this->destino = $destino;
    }
    //getter
    public function getIdRuta()
    {
        return $this->idRuta;
    }
    public function getDuracionEstimada()
    {
        return $this->duracion_estimada;
    }
    public function getDistanciaKM()
    {
        return $this->distancia_KM;
    }
    public function getOrigen()
    {
        return $this->origen;
    }
    public function getDestino()
    {
        return $this->destino;
    }
    //setter
    public function setIdRuta($idRuta)
    {
        $this->idRuta = $idRuta;
    }
    public function setDuracionEstimada($duracion_estimada)
    {
        $this->duracion_estimada = $duracion_estimada;
    }
    public function setDistanciaKM($distancia_KM)
    {
        $this->distancia_KM = $distancia_KM;
    }
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    public function obtenerRutaId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $rutaDAO = new RutaDAO($this->idRuta, null, null, null, null);
        try {
            $sql = $rutaDAO->obtenerRutaId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {
                $this->duracion_estimada = $fila[0];
                $this->distancia_KM = $fila[1];

                $ciudadOrigen = new Ciudad($fila[2]);
                $ciudadOrigen->obtenerCiudadId();
                $this->origen = $ciudadOrigen;

                $ciudadDestino = new Ciudad($fila[3]);
                $ciudadDestino->obtenerCiudadId();
                $this->destino = $ciudadDestino;
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }
}