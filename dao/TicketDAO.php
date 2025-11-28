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

    public function crearTicket()
    {
        return [
            "sql" => "INSERT INTO `g2_ticket`(`Estado_Ticket_idEstado_Ticket`, `Precio`, `Puesto`, `Pasajero_idPasajero`, `Vuelo_idVuelo`) 
                VALUES (:estado, :precio, :puesto, :pasajero, :vuelo)",
            "parametros" => [
                ":estado"=>$this->estado_ticket,
                ":precio"=>$this->precio,
                ":puesto"=>$this->puesto,
                ":pasajero"=>$this->pasajero,
                ":vuelo" => $this->vuelo
            ]
            ];
    }

    public function obtenerTicketIdVuelo($vuelo)
    {
        return [
            "sql"=>"SELECT `idTicket`, `Pasajero_idPasajero`
                FROM `g2_ticket` 
                WHERE `Vuelo_idVuelo`= :vuelo
                AND `Estado_Ticket_idEstado_Ticket`!= 2",
            "parametros"=>[
                ":vuelo"=>$vuelo
            ]    
        ];
    }

    public function actualizarCheckinOpen($ticket){
        return [
            "sql"=>"UPDATE `g2_ticket` 
                    SET `Estado_Ticket_idEstado_Ticket`= 2 
                    WHERE `idTicket`= :ticket ;",
            "parametros"=>[
                ":ticket"=>$ticket
            ]
        ];
    }

    public function actualizarCheckinClose($ticket)
    {
        return [
            "sql" => "UPDATE g2_ticket 
                    SET Estado_Ticket_idEstado_Ticket = 3
                    WHERE idTicket = :id",
            "parametros" => [
                ":id" => $ticket
            ]
        ];
    }

    public function actualizarTiketsInAirP($idTicket)
    {
        return [
            "sql"=>"UPDATE `g2_ticket` 
                SET `Estado_Ticket_idEstado_Ticket`=5
                WHERE `idTicket`= :ticket ",
            "parametros"=>[
                ":ticket"=>$idTicket
            ]
        ];
    }

    public function actualizarTiketsInAirN($idTicket)
    {
        return [
            "sql"=>"UPDATE `g2_ticket` 
                SET `Estado_Ticket_idEstado_Ticket`=6
                WHERE `idTicket`= :ticket ",
            "parametros"=>[
                ":ticket"=>$idTicket
            ]
        ];
    }

    public function consutarCheckin()
    {
        return [
            "sql"=>"SELECT `idTicket`, `Check_in` 
                FROM `g2_ticket` 
                WHERE `idTicket`= :vuelo",
            "parametros"=>[
                ":vuelo"=>$this->vuelo
            ]
        ];
    }

    public function cambiarEstadoCheckin()
    {
        return [
            "sql"=>"UPDATE `g2_ticket` 
                SET `Check_in`= 1 
                WHERE `idTicket`= :ticket",
            "parametros"=>[
                ":ticket"=>$this->idTicket
            ]
        ];
    }

    public function obtenerTicketsPasajero()
    {
        return [
            "sql" => "SELECT `idTicket`, `Estado_Ticket_idEstado_Ticket`, `Precio`, `Puesto`, `Vuelo_idVuelo`, `Check_in` 
                    FROM `g2_ticket` 
                    WHERE  `Pasajero_idPasajero` = :pasajero ",
            "parametros" => [
                ":pasajero" => $this->pasajero
            ]
        ];
    }

    public function destinosFrecuentes()
    {
        return [
            "sql" => "SELECT c.Nombre, COUNT(*) AS viajes
                    FROM g2_ticket t
                    INNER JOIN g2_vuelo v ON t.Vuelo_idVuelo = v.idVuelo
                    INNER JOIN g2_ruta r ON v.Ruta_idRuta = r.idRuta
                    INNER JOIN g2_ciudad c ON r.Destino = `idCiudad`
                    WHERE t.Pasajero_idPasajero = :p
                    GROUP BY r.Destino
                    ORDER BY viajes DESC",
            "parametros" => [
                ":p" => $this->pasajero
            ]
        ];
    }

    public function obtenerVuelosPorMes()
    {
        return [
            "sql" => "
                SELECT MONTH(v.Fecha) AS mes, COUNT(*) AS total
                FROM g2_ticket t
                INNER JOIN g2_vuelo v ON t.Vuelo_idVuelo = v.idVuelo
                WHERE t.Pasajero_idPasajero = :id
                GROUP BY mes
                ORDER BY mes
            ",
            "parametros" => [
                ":id" => $this->pasajero
            ]
        ];
    }


}