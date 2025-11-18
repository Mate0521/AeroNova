<?php

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
}