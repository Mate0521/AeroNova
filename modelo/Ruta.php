<?php
<<<<<<< HEAD
require_once(__DIR__ . '/../dao/RutaDAO.php');
require_once(__DIR__ . '/../modelo/Ciudad.php');
require_once(__DIR__ . '/../config/Conexion.php');

=======
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/RutaDAO.php');
>>>>>>> feature/Mateo
class Ruta
{
    private $idRuta;
    private $duracion_estimada;
    private $distancia_KM;
    private $origen;  // objeto Ciudad
    private $destino; // objeto Ciudad

    public function __construct($idRuta = null, $duracion_estimada = null, $distancia_KM = null, $origen = null, $destino = null)
    {
        $this->idRuta = $idRuta;
        $this->duracion_estimada = $duracion_estimada;
        $this->distancia_KM = $distancia_KM;
        $this->origen = $origen;
        $this->destino = $destino;
    }

    // Getters
    public function getIdRuta() { return $this->idRuta; }
    public function getDuracionEstimada() { return $this->duracion_estimada; }
    public function getDistanciaKM() { return $this->distancia_KM; }
    public function getOrigen() { return $this->origen; }
    public function getDestino() { return $this->destino; }

    // Setters
    public function setIdRuta($idRuta) { $this->idRuta = $idRuta; }
    public function setDuracionEstimada($duracion_estimada) { $this->duracion_estimada = $duracion_estimada; }
    public function setDistanciaKM($distancia_KM) { $this->distancia_KM = $distancia_KM; }
    public function setOrigen($origen) { $this->origen = $origen; }
    public function setDestino($destino) { $this->destino = $destino; }

    // Métodos nuevos para asignar nombres directamente
    public function setOrigenNombre($nombre)
    {
        $this->origen = new Ciudad(null, $nombre);
    }

    public function setDestinoNombre($nombre)
    {
        $this->destino = new Ciudad(null, $nombre);
    }

    // Obtener ruta desde la base de datos por id
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
<<<<<<< HEAD
}
?>
=======

    public function convertirTimeAHoras()
    {
        $time=$this->duracion_estimada;
        list($h, $m, $s) = explode(":", $time);
        return $h + ($m / 60) + ($s / 3600);
    }

}
>>>>>>> feature/Mateo
