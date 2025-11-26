<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/TicketDAO.php');

class Ticket
{
    private $idTicket;
    private $estado_ticket;//ob estado, consulta cascada
    private $precio;
    private $puesto;
    private $pasajero;//ob pasajero, consulta cascada
    private $vuelo;//ob vuelo, consulta cascada
    private $check_in;

    // Constructor
    public function __construct($idTicket = null, $estado_ticket = null, $precio = null, $puesto = null, $pasajero = null, $vuelo = null, $check_in = null)
    {
        $this->idTicket = $idTicket;
        $this->estado_ticket = $estado_ticket;
        $this->precio = $precio;
        $this->puesto = $puesto;
        $this->pasajero = $pasajero;
        $this->vuelo = $vuelo;
        $this->check_in = $check_in;
    }
    // Getters
    public function getIdTicket()
    {
        return $this->idTicket;
    }
    public function getEstadoTicket()
    {
        return $this->estado_ticket;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getPuesto()
    {
        return $this->puesto;
    }
    public function getPasajero()
    {
        return $this->pasajero;
    }
    public function getVuelo()
    {
        return $this->vuelo;
    }
    public function getCheckIn()
    {
        return $this->check_in;
    }
    //
    // Setters
    public function setEstadoTicket($estado_ticket)
    {
        $this->estado_ticket = $estado_ticket;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }
    public function setPasajero($pasajero)
    {
        $this->pasajero = $pasajero;
    }
    public function setVuelo($vuelo)
    {
        $this->vuelo = $vuelo;
    }
    public function setCheckIn($check_in)
    {
        $this->check_in = $check_in;
    }

    public function obtenerTicketId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO($this->idTicket, null, null, null, null, null, null);
        try {
            $sql = $ticketDAO->obtenerTicketId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {

                $estadoOB = new Estado($fila[0]);
                $estadoOB->obtenerEstadoTicketId();
                $this->estado_ticket = $estadoOB;

                $this->precio = $fila[1];
                $this->puesto = $fila[2];

                $pasajeroOB = new Pasajero($fila[3]);
                $pasajeroOB->obtenerPasajeroId();
                $this->pasajero = $pasajeroOB;

                $vueloOB = new Vuelo($fila[4]);
                $vueloOB->obtenerVueloId();
                $this->vuelo = $vueloOB;
                $this->check_in = $fila[5];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function calcularPrecioBase()
    {
        //lo dividimos en partes 
        //consultamos el vuelo a partir de su distancia, obtenemos el valor del brent y calculamos el precio de conbustible
        //luego sumamos el costo de operacion a partir de la distancia
        //agregamos el margen de ganancia 
        //tambien toamamos del vuelo la ocupacion que tienen respecto a la fecha 

  
        $url = 'https://api.oilpriceapi.com/v1/prices/latest';
    
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Token 6030acac3b094f7051382129004089a73fc40359c1a830a6902a65a0aac05143"
        ]);
        $datos = curl_exec($curl);   

        $productos = json_decode($datos, true);
        
        $precioBrent = $productos["data"]["price"]; //aqui esta el precio del brent barril
        
        $vuelo =new Vuelo($this->vuelo);
        $vuelo->obtenerVueloId();
        $duracion = $vuelo->getRuta()->convertirTimeAHoras();

        $costoCombustible = (($precioBrent/159*0.85)+0.20)*4*$duracion;//primera variable costo del combustible
        $costoOperacion = 180*$duracion; //segunda variable costo de operacion
        $precioBase = ($costoCombustible + $costoOperacion)*1.30; // ganancia de 30

        $ocupacion = $vuelo->calcularOcupacion( $this->cantidadParaVuelo());

        switch (true) {
            case ($ocupacion > 90):
                $factor = 1.80;
                break;
            case ($ocupacion > 60):
                $factor = 1.45; 
                break;
            case ($ocupacion > 30):
                $factor = 1.15; 
                break;
            default:
                $factor = 0.90; 
        }

        return$precioBase *= $factor;
    }

    public function cantidadParaVuelo()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO("", "", "", "", "", $this->vuelo);
        try {
            $sql = $ticketDAO->cantidadParaVuelo();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $cantidad = $conexion->registro();
            $conexion->cerrar();
            return $cantidad[0];
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function crearTicket()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO("", $this->estado_ticket, $this->precio, $this->puesto, $this->pasajero, $this->vuelo);
        try {
            $sql = $ticketDAO->crearTicket();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $this->idTicket=$conexion->lastID();
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

}