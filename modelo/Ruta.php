<?php
require_once(__DIR__ . '/../dao/RutaDAO.php');
require_once(__DIR__ . '/../modelo/Ciudad.php');
require_once(__DIR__ . '/../config/Conexion.php');

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
<<<<<<< Updated upstream
}
?>
=======

public function convertirTimeAHoras() {
    $time = $this->duracion_estimada;

    if (empty($time)) return 0;

    $parts = explode(":", $time);
    $h = isset($parts[0]) ? (int)$parts[0] : 0;
    $m = isset($parts[1]) ? (int)$parts[1] : 0;
    $s = isset($parts[2]) ? (int)$parts[2] : 0;

    return $h + ($m / 60) + ($s / 3600);
}


public function obtenerRutas()
{
    $conexion = new Conexion();
    $conexion->abrir();
    $rutaDAO = new RutaDAO();
    $rutas = [];

    try {
        $sql = $rutaDAO->obtenerRutas();
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        while ($fila = $conexion->registro()) {
            // SQL: idRuta, Duracion_Estimada, Distancia_KM, Origen, Destino
            $idRuta           = $fila[0];
            $duracionEstimada = $fila[1];
            $distanciaKM      = $fila[2];
            $idOrigen         = $fila[3];
            $idDestino        = $fila[4];

            // ✅ Instanciar ciudades con sus IDs
            $ciudadOrigen = new Ciudad($idOrigen);
            $ciudadOrigen->obtenerCiudadId();

            $ciudadDestino = new Ciudad($idDestino);
            $ciudadDestino->obtenerCiudadId();

            // ✅ Construir la ruta con datos correctos
            $ruta = new Ruta($idRuta, $duracionEstimada, $distanciaKM, $ciudadOrigen, $ciudadDestino);
            $rutas[] = $ruta;
        }

        $conexion->cerrar();
        return $rutas;

    } catch (Exception $e) {
        $conexion->cerrar();
        return $e;
    }
}


}

    
?>
>>>>>>> Stashed changes
