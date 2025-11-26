<?php
class EstadoDAO
{
    private $idEstado;
    private $valor;

    public function __construct($idEstado = null, $valor = null)
    {
        $this->idEstado = $idEstado;
        $this->valor = $valor;
    }

    public function obtenerEstadoVueloId()
    {
        return [
            "sql" => "SELECT `Valor` 
                    FROM `g2_estado_vuelo` 
                    WHERE `idEstado_Vuelo`= :idEstado",
            "parametros" => [
                ":idEstado" => $this->idEstado
            ]
        ];
    }
    public function obtenerEstadoTicketId()
    {
        return [
            "sql" => "SELECT `Valor` 
                    FROM `g2_estado_ticket`      
                    WHERE `idEstado_Ticket`= :idEstado",
            "parametros" => [
                ":idEstado" => $this->idEstado
            ]
        ];
    }
    public function obtenerEstadoPilotoId()
    {
        return [
            "sql" => "SELECT `Valor` 
                    FROM `g2_estado_piloto`      
                    WHERE `id_estado`= :idEstado",
            "parametros" => [
                ":idEstado" => $this->idEstado
            ]
        ];
    }
}