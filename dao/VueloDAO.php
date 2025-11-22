<?php
class VueloDAO
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

    public function obtenerVueloId()
    {
        return [
            "sql" => "SELECT `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo` 
                    FROM `g2_vuelo` 
                    WHERE `idVuelo` = :idVuelo",
            "parametros" => [
                ":idVuelo" => $this->idVuelo
            ]
        ];
    }
}