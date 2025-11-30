<?php
require_once(__DIR__ . '/../modelo/Avion.php');
require_once(__DIR__ . '/../modelo/Ruta.php');
require_once(__DIR__ . '/../modelo/Piloto.php');
require_once(__DIR__ . '/../modelo/Estado.php');
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/VueloDAO.php');


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
    public function consultarVuelos($limit=20, $offset=1)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO();
        $vuelos = [];
        try {
            $sql = $vueloDAO->consultarVuelos($limit, $offset);
            $conexion->ejecutar($sql["sql"], array_merge($sql["parametros"], $this->vuelosDisponibles()));
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

    public function calcularOcupacion($cantTickets)
    {

        $capacidadAvion = $this->avion->getCapacidad();

        $fechaVuelo = new DateTime($this->fecha . ' ' . $this->hora_despegue);
        $hoy = new DateTime();
        
        if ($hoy > $fechaVuelo) return 100; 

        $diasRestantes = (int)$hoy->diff($fechaVuelo)->format("%a");

        
        $ventanaRelevante = 90; 
        $diasAjustados = min($diasRestantes, $ventanaRelevante);
        
        $normTiempo = (1 - ($diasAjustados / $ventanaRelevante)) * 100;

        $normCapacidad = ($cantTickets / $capacidadAvion) * 100;
        
        if ($normCapacidad > 100) $normCapacidad = 100;

        $pesoTiempo = 0.4;
        $pesoCapacidad = 0.6;

        $indice = ($normTiempo * $pesoTiempo) + ($normCapacidad * $pesoCapacidad);

        return round($indice, 2);
    }

    public function buscarVueloDestino($filtro, $limit, $offset)
    {
        $conexion = new Conexion();
        $vueloDAO = new VueloDAO();
        $conexion->abrir();
        $vuelos = [];
        try {
            $sql = $vueloDAO->buscarVuelo($filtro, $limit, $offset);
            $conexion->ejecutar($sql["sql"], array_merge($sql["parametros"], $this->vuelosDisponibles()));
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

    public function vuelosDisponibles()
    {
        $hoy = new DateTime();
        return [
            ":ahora" => $hoy->format('Y-m-d H:i:s'),
            ":estado" => 1
        ];
    }

    public function obtenerAsientosOcupados(): array
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO($this->idVuelo);
        $sql =$vueloDAO->obtenerAsientosOcupados();
        $conexion->ejecutar($sql['sql'],$sql['parametros']);
        $asientos = [];

        while ($fila = $conexion->registro()) {
            $asientos[] = $fila[0];
        }

        $conexion->cerrar();
        return $asientos;
    }


    public function asignarAsiento($claseDeseada)
    {
        
        $capacidad = $this->avion->getCapacidad();


        $primeraClase = round($capacidad * 0.25);
        $business     = round($capacidad * 0.35);
        $economica    = $capacidad - ($primeraClase + $business);


        $rangos = [
            "bus" => [1, $primeraClase],
            "clas" => [$primeraClase + 1, $primeraClase + $business],
            "eco" => [$primeraClase + $business + 1, $capacidad]
        ];

        list($inicio, $fin) = $rangos[$claseDeseada];


        $ocupados = $this->obtenerAsientosOcupados();

        $ocupadosSet = array_flip($ocupados);

        for ($i = $inicio; $i <= $fin; $i++) {

            if (!isset($ocupadosSet[$i])) {
                return $i;
            }
        }

        return null;
    }

    public function consultarVuelosProcesoAbordaje()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO();
        $vuelos = [];
        try {
            $sql = $vueloDAO->consultarVuelosProcesoAbordaje();
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
    
    public function consultarVuelosPorPiloto($piloto)//lista de 5 vuelos
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO();
        $vuelos = [];
        try {
            $sql = $vueloDAO->consultarVuelosPorPiloto($piloto);
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

                $estadoOB = new Estado($fila[8],null);
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

    public function consultarVuelosPorEstado($idPiloto, $estado)
{
    $conexion = new Conexion();
    $conexion->abrir();
    $vueloDAO = new VueloDAO();
    $vuelos = [];

    try {
        // Obtenemos la consulta SQL y parámetros desde el DAO
        $sql = $vueloDAO->consultarVuelosPorEstado($idPiloto, $estado);

        // Ejecutamos la consulta
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        // Iteramos los resultados
        while ($fila = $conexion->registro()) {

            // Creamos el objeto Vuelo con los campos básicos
            $vuelo = new Vuelo(
                $fila[0], // idVuelo
                $fila[1], // Fecha
                $fila[2], // Hora_Despegue
                null,
                null,
                null,
                null,
                $fila[7], // Hora_Llegada
                null
            );

            // Piloto principal
            $pilotoOB = new Piloto($fila[3]);
            $pilotoOB->obtenerPilotoId();
            $vuelo->setPilotoPrincipal($pilotoOB);

            // Copiloto
            $copilotoOB = new Piloto($fila[4]);
            $copilotoOB->obtenerPilotoId();
            $vuelo->setCopiloto($copilotoOB);

            // Avión
            $avionOB = new Avion($fila[5]);
            $avionOB->obtenerAvionMatricula();
            $vuelo->setAvion($avionOB);

            // Ruta
            $rutaOB = new Ruta($fila[6]);
            $rutaOB->obtenerRutaId();
            $vuelo->setRuta($rutaOB);

            // Estado del vuelo
            $estadoOB = new Estado($fila[8], null);
            $estadoOB->obtenerEstadoVueloId();
            $vuelo->setEstadoVuelo($estadoOB);

            // Guardamos el vuelo en el array
            $vuelos[] = $vuelo;
        }

        $conexion->cerrar();
        return $vuelos;

    } catch (Exception $e) {
        $conexion->cerrar();
        return $e;
    }
}

    
public function CambiarEstado($idVuelo, $nuevoEstado) {
    $conexion = new Conexion();
    $conexion->abrir();
    $vueloDAO = new VueloDAO();

    try {

        $sql = $vueloDAO->CambiarEstado($idVuelo, $nuevoEstado);

        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        $conexion->cerrar();
        return true;

    } catch (Exception $e) {
        $conexion->cerrar();
        return $e;
    }
}

public function buscar($filtro)
{
    $conexion = new Conexion();
    $conexion->abrir();

    $vueloDAO = new VueloDAO();
    $consulta = $vueloDAO->buscar($filtro);
    $conexion->ejecutar($consulta["sql"], $consulta["parametros"]);

    $vuelos = array();

    while (($tupla = $conexion->registro()) != null) {

        // Piloto principal
        $pilotoPrincipal = new Piloto($tupla[5]);
        $pilotoPrincipal->obtenerPilotoId();

        // Copiloto
        $copiloto = new Piloto($tupla[6]);
        $copiloto->obtenerPilotoId();

        // Avión
        $avion = new Avion($tupla[7]);
        $avion->obtenerAvionMatricula();

        // Ruta
        $ruta = new Ruta($tupla[8]); // idRuta
        $ruta->setOrigenNombre($tupla[9]);   // nombre origen
        $ruta->setDestinoNombre($tupla[10]); // nombre destino

        // Estado
        $estado = new Estado($tupla[11]);
        $estado->obtenerEstadoVueloId();

        // Creamos el objeto Vuelo completo
        $vuelo = new Vuelo(
            $tupla[0], // idVuelo
            $tupla[1], // fecha
            $tupla[2], // hora despegue
            $pilotoPrincipal,
            $copiloto,
            $avion,
            $ruta,
            $tupla[3], // hora llegada
            $estado     // estado objeto
        );

        array_push($vuelos, $vuelo);
    }

    $conexion->cerrar();
    return $vuelos;
}

    public function actualizarVueloInAir()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO($this->idVuelo);
        try {
            $sql=$vueloDAO->actualizarVueloInAir();
            $conexion->ejecutar($sql['sql'],$sql['parametros']);
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }

    }
    public function consultarVuelosFinalizar()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO();
        $vuelos = [];

        try {
            $sql = $vueloDAO->consultarVuelosFinalizar();
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
    
    public function actualizarVueloFinalizado()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $vueloDAO = new VueloDAO($this->idVuelo);

        try {
            $sql = $vueloDAO->actualizarVueloFinalizado();
            $conexion->ejecutar($sql['sql'], $sql['parametros']);
            $conexion->cerrar();

        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }



}