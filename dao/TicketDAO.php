<?php
class TicketDAO
{
    private $idTicket;
    private $estado_ticket;
    private $precio;
    private $puesto;
    private $pasajero;
    private $vuelo;
    private $check_in;

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

    public function obtenerTicketId()
    {
        return [
            "sql" => "SELECT `Estado_Ticket_idEstado_Ticket`, `Precio`, `Puesto`, `Pasajero_idPasajero`, `Vuelo_idVuelo`, `Check_in` 
                    FROM `g2_ticket` 
                    WHERE `idTicket`= :idTicket",
            "parametros" => [
                ":idTicket" => $this->idTicket
            ]
        ];
    }
    
    public function cantidadParaVuelo()
    {
        return [
            "sql" => "SELECT COUNT(*) FROM `g2_ticket` WHERE `Vuelo_idVuelo` = :vuelo",
            "parametros" => [
                ":vuelo" => $this->vuelo
            ]
        ];
    }
}