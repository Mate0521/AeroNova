<?php
class AvionDAO
{
    private $matricula;
    private $modelo;
    private $capacidad;

    public function __construct($matricula = null, $modelo = null, $capacidad = null)
    {
        $this->matricula = $matricula;
        $this->modelo = $modelo;
        $this->capacidad = $capacidad;
    }

    public function obtenerAvionMatricula()
    {
        return [
            "sql" => "SELECT `Modelo`, `Capacidad` 
                    FROM `g2_avion` 
                    WHERE `Matricula`= :matricula",
            "parametros" => [
                ":matricula" => $this->matricula
            ]
        ];
    }
    public function consultar()
    {
        return [
            "sql" => "SELECT `Matricula`, `Modelo`, `Capacidad` FROM `g2_avion` ",
            "parametros" => []
        ];
    }

    public function actualizarModelo()
    {
        return [
            "sql" => "UPDATE `g2_avion` 
            SET modelo = :modelo
            WHERE matricula = :matricula",
            "parametros" => [
                ":modelo"=>$this->modelo, 
                ":matricula"=>$this->matricula
            ]
        ];
    }

    public function actualizarCapacidad()
    {
        return [
            "sql" => "UPDATE `g2_avion` 
            SET capacidad = :cap
            WHERE matricula = :matricula",
            "parametros" => [
                ":cap"=>$this->capacidad, 
                ":matricula"=>$this->matricula
            ]
        ];
    }

    public function obtenerHistorialVuelos()
    {
        return [
            "sql" =>
            "SELECT 
                v.idVuelo,v.Fecha, v.Hora_Despegue , v.Hora_Llegada ,
                co.idCiudad , co.Nombre , cd.idCiudad , cd.Nombre ,
                ev.idEstado_Vuelo , ev.Valor 
                FROM g2_vuelo v
                INNER JOIN g2_ruta r ON v.Ruta_idRuta = r.idRuta
                INNER JOIN g2_ciudad co ON r.Origen = co.idCiudad
                INNER JOIN g2_ciudad cd ON r.Destino = cd.idCiudad
                INNER JOIN g2_estado_vuelo ev ON v.Estado_Vuelo_idEstado_Vuelo = ev.idEstado_Vuelo
                WHERE v.avion_matricula = :matricula
                ORDER BY v.Fecha DESC, v.Hora_Despegue DESC;",

            "parametros" => [
                "matricula"=>$this->matricula
            ]
        ];
    }

    public function buscarModelos($texto)
    {
        return [
            "sql" => "SELECT DISTINCT modelo 
                    FROM `g2_avion`
                    WHERE `Modelo` LIKE :modelo
                    ORDER BY modelo 
                    LIMIT 5",
            "parametros" => [
                ":modelo"=> "%" . $texto . "%"
            ]
        ];
    }

    public function crear()
    {
        return [
            "sql" => "INSERT INTO `g2_avion` (`Matricula`, `Modelo`, `Capacidad`) 
            VALUES (:matricula, :modelo, :capacidad)",
            "parametros" => [
                ":matricula"=>$this->matricula, 
                ":modelo"=>$this->modelo, 
                ":capacidad"=>$this->capacidad
            ]
        ];
    }

    public function buscarAvion($texto)
    {
        return [
            "sql"=>"SELECT `Matricula`, `Modelo`, `Capacidad` FROM `g2_avion` 
                WHERE `Matricula` LIKE :mat
                OR `Modelo` LIKE :model 
                ORDER BY `Matricula`",
            "parametros"=>[
                ":mat"=>"%".$texto."%",
                ":model"=>"%".$texto."%",
            ]
        ];
    }



}