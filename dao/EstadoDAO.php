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
                  WHERE `idEstado_Vuelo` = :idEstado",
        "parametros" => [
            ":idEstado" => $this->idEstado
        ]
    ];
}
public function obtenerEstadoVuelo()
{
    return [
        "sql" => "SELECT `idEstado_Vuelo`, `Valor` FROM `g2_estado_vuelo`",
        "parametros" => [] 
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
public function obtenerEstadoPilotoId() {
        return [
            "sql" => "SELECT `Valor` 
                      FROM `g2_estado_piloto`      
                      WHERE `id_estado` = :idEstado",
            "parametros" => [
                ":idEstado" => $this->idEstado
            ]
        ];
    }
    public function consultarVuelosPorEstado($estado)
{
    return [
        "sql" => "SELECT idVuelo, Fecha, Hora_Despegue, Piloto_principal, Copiloto,
                         Avion_Matricula, Ruta_idRuta, Hora_Llegada, Estado_Vuelo_idEstado_Vuelo
                  FROM g2_vuelo
                  WHERE Estado_Vuelo_idEstado_Vuelo = ?",
        "parametros" => [$estado]
    ];
}

public function obtenerEstadoPilotoS() {
    return [
        "sql" => "SELECT id_estado, Valor FROM g2_estado_piloto",
        "parametros" => []
    ];
}

}