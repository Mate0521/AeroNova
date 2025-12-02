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

    public function consultarVuelos($limit, $offset)
    {
        return [
            "sql" => "SELECT `idVuelo`, `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo` 
                    FROM `g2_vuelo`
                    WHERE CONCAT(Fecha, ' ', Hora_Despegue) >= :ahora
                    AND `Estado_Vuelo_idEstado_Vuelo` = :estado
                    ORDER BY `idVuelo`
                    LIMIT :limit 
                    OFFSET :offset",
            "parametros" => [
                ":offset" => $offset,
                ":limit" => $limit
            ]
        ];
    }

public function consultarVuelosPorPiloto($piloto)
{
    return [
        "sql" => "SELECT idVuelo, Fecha, Hora_Despegue, Piloto_principal, Copiloto, Avion_Matricula, Ruta_idRuta, Hora_Llegada, Estado_Vuelo_idEstado_Vuelo
                  FROM g2_vuelo
                  WHERE Piloto_principal = ?",
        "parametros" => [$piloto]
    ];
}
    public function consultarVuelosPorEstado($idPiloto, $estado)
{
    return [
        "sql" => "SELECT idVuelo, Fecha, Hora_Despegue, Piloto_principal, Copiloto,
                         Avion_Matricula, Ruta_idRuta, Hora_Llegada, Estado_Vuelo_idEstado_Vuelo
                  FROM g2_vuelo
                  WHERE Estado_Vuelo_idEstado_Vuelo = ?
                  AND Piloto_principal = ?",
        "parametros" => [$estado, $idPiloto]
    ];
}

public function CambiarEstado($idVuelo, $nuevoEstado) {
    return [
        "sql" => "UPDATE g2_vuelo 
                  SET Estado_Vuelo_idEstado_Vuelo = :estado 
                  WHERE idVuelo = :id",
        "parametros" => [
            ":estado" => $nuevoEstado,
            ":id"     => $idVuelo
        ]
    ];
}
public function buscar($filtro)
{
    return [
        "sql" => 
        "SELECT v.idVuelo, v.Fecha, v.Hora_Despegue, v.Hora_Llegada,
                p1.nombre AS piloto,
                p2.nombre AS copiloto,
                a.modelo, a.matricula, a.capacidad,
                co.nombre AS origen,
                cd.nombre AS destino,
                r.idRuta,
                e.valor AS estado
        FROM g2_vuelo v
        INNER JOIN g2_piloto p1 ON v.Piloto_principal = p1.idPiloto
        INNER JOIN g2_piloto p2 ON v.Copiloto = p2.idPiloto
        INNER JOIN g2_avion a ON v.Avion_Matricula = a.matricula
        INNER JOIN g2_ruta r ON v.Ruta_idRuta = r.idRuta
        INNER JOIN g2_ciudad co ON r.Origen = co.idCiudad
        INNER JOIN g2_ciudad cd ON r.Destino = cd.idCiudad
        INNER JOIN g2_estado_vuelo e ON v.Estado_Vuelo_idEstado_Vuelo = e.idEstado_Vuelo
        WHERE p2.nombre LIKE ?
           OR a.modelo LIKE ?
           OR a.matricula LIKE ?
           OR co.nombre LIKE ?
           OR cd.nombre LIKE ?
        ",
        "parametros" => [
            "%$filtro%",
            "%$filtro%",
            "%$filtro%",
            "%$filtro%",
            "%$filtro%"
        ]
    ];
}


    public function buscarVuelo($filtro, $limit, $offset)
    {
        return [
            "sql" => "SELECT `idVuelo`, `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo` 
                    FROM `g2_vuelo` v
                    JOIN `g2_ruta` r ON v.Ruta_idRuta = r.idRuta
                    JOIN `g2_ciudad` c1 ON r.Origen = c1.idCiudad
                    JOIN `g2_ciudad` c2 ON r.Destino = c2.idCiudad
                    WHERE (c1.Nombre LIKE :filtro1 OR c2.Nombre LIKE :filtro2) 
                    AND CONCAT(Fecha, ' ', Hora_Despegue) >= :ahora 
                    AND Estado_Vuelo_idEstado_Vuelo = :estado
                    ORDER BY `idVuelo`
                    LIMIT :limit 
                    OFFSET :offset"
                    ,
            "parametros" => [
                ":filtro1"   => '%' . $filtro . '%',
                ":filtro2"   => '%' . $filtro . '%',
                ":offset" => $offset,
                ":limit" => $limit
            ]
        ];

    }

    public function obtenerAsientosOcupados(){
        return [
            "sql"=>"SELECT  `Puesto` 
                FROM `g2_ticket` 
                WHERE `Vuelo_idVuelo`= :idVuelo",
            "parametros"=>[
                ":idVuelo"=>$this->idVuelo
            ]
        ];
    }

    public function consultarVuelosProcesoAbordaje()
    {
        $ahora = new DateTime();
        return [
            "sql" => "SELECT `idVuelo`, `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo` 
                    FROM `g2_vuelo`
                    WHERE TIMESTAMPDIFF(MINUTE, CONCAT(Fecha, ' ', Hora_Despegue), :ahora ) BETWEEN -:margen AND :margen1
                    AND `Estado_Vuelo_idEstado_Vuelo` !=  2",
            "parametros" => [
                ":ahora"  => $ahora->format("Y-m-d H:i:s"),
                ":margen" => 10,
                ":margen1" => 10
            ]
        ];
    }

    public function actualizarVueloInAir()
    {
        return [
            "sql"=>"UPDATE `g2_vuelo` 
                SET `Estado_Vuelo_idEstado_Vuelo`= 2 
                WHERE `idVuelo`= :vuelo",
            "parametros"=>[
                ":vuelo"=>$this->idVuelo
            ]
        ];
    }

    public function consultarVuelosFinalizar()
    {
        $ahora = new DateTime();
        return [
            "sql" => "SELECT `idVuelo`, `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo`
                    FROM `g2_vuelo`
                    WHERE TIMESTAMPDIFF(MINUTE, CONCAT(Fecha, ' ', Hora_Llegada), :ahora) 
                            BETWEEN -:margen AND :margen1
                    AND `Estado_Vuelo_idEstado_Vuelo` = 2",
            "parametros" => [
                ":ahora"  => $ahora->format("Y-m-d H:i:s"),
                ":margen" => 10,
                ":margen1" => 10
            ]
        ];
    }

    public function actualizarVueloFinalizado()
    {
        return [
            "sql" => "UPDATE `g2_vuelo` 
                    SET `Estado_Vuelo_idEstado_Vuelo` = 3 
                    WHERE `idVuelo` = :vuelo",
            "parametros" => [
                ":vuelo" => $this->idVuelo
            ]
        ];
    }

    public function consultarVuelosLigh()
    {
        return [
            "sql" => "SELECT `idVuelo`, `Fecha`, `Hora_Despegue`, `Piloto_principal`, `Copiloto`, `Avion_Matricula`, 
                                `Ruta_idRuta`, `Hora_Llegada`, `Estado_Vuelo_idEstado_Vuelo` 
                    FROM `g2_vuelo`",
            "parametros" => []
        ];
    }

}