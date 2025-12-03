<?php

class RutaDAO
{
    private $idRuta;
    private $duracion_estimada;
    private $distancia_KM;
    private $origen;
    private $destino;

    public function __construct($idRuta = null, $duracion_estimada = null, $distancia_KM = null, $origen = null, $destino = null)
    {
        $this->idRuta = $idRuta;
        $this->duracion_estimada = $duracion_estimada;
        $this->distancia_KM = $distancia_KM;
        $this->origen = $origen;
        $this->destino = $destino;
    }

    public function obtenerRutaId()
    {
        return [
            "sql" => "SELECT `Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino` 
                    FROM `g2_ruta` 
                    WHERE `idRuta`= :idRuta",
            "parametros" => [
                ":idRuta" => $this->idRuta
            ]
        ];
    }
    public function consultar()
    {
        return [
            "sql"=>"SELECT `idRuta`, `Duracion_Estimada`, `Distancia_KM`, `Origen`,`Destino` 
                FROM `g2_ruta`",
            "parametros"=>[]
        ];
    }

    public function validarRuta()
    {
        return [
            "sql"=>"SELECT `idRuta` 
                FROM `g2_ruta` 
                WHERE `Origen`= :origen 
                AND `Destino`= :destino
                AND`idRuta`!= :id",
            "parametros"=>[
                ":origen"=>$this->origen,
                ":destino"=>$this->destino,
                ":id"=>$this->idRuta
            ]
        ];
    }

    public function actualizarDuracion()
    {
        return [
            "sql" => "UPDATE `g2_ruta` 
                SET `Duracion_Estimada` = :duracion
                WHERE `idRuta` = :id",
            "parametros" => [
                ":duracion"=>$this->duracion_estimada, 
                ":id"=>$this->idRuta
            ]
        ];
    }

    public function actualizarDistancia()
    {
        return [
            "sql" => "UPDATE `g2_ruta` 
                SET `Distancia_KM` = :distancia
                WHERE `idRuta` = :id",
            "parametros" => [
                ":distancia"=>$this->distancia_KM, 
                ":id"=>$this->idRuta
            ]
        ];
    }

    public function actualizarOrigen()
    {
        return [
            "sql" => "UPDATE `g2_ruta` 
                SET `Origen` = :orig 
                WHERE `idRuta` = :id",
            "parametros" => [
                ":orig"=>$this->origen, 
                ":id"=>$this->idRuta
            ]
        ];
    }

    public function actualizarDestino()
    {
        return [
            "sql" => "UPDATE `g2_ruta` 
                SET `Destino` = :dest
                WHERE `idRuta` = :id",
            "parametros" => [
                ":dest"=>$this->destino, 
                ":id"=>$this->idRuta
            ]
        ];
    }

    public function crearRuta()
    {
        return [
            "sql"=>"INSERT INTO `g2_ruta`(`Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino`) 
                VALUES ( :dur , :dist , :orig , :dest)",
            "parametros"=>[
                ":dur"=>$this->duracion_estimada,
                ":dist"=>$this->distancia_KM,
                ":orig"=>$this->origen,
                ":dest"=>$this->destino
            ]
        ];
    }

    public function buscarRuta($texto)
    {
        return [
            "sql"=>"SELECT `idRuta`, `Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino` 
                FROM `g2_ruta` r INNER JOIN g2_ciudad co ON r.Origen=co.idCiudad 
                INNER JOIN g2_ciudad cd ON r.Destino=cd.idCiudad
                WHERE 
                co.Nombre LIKE :orig
                OR cd.Nombre LIKE :dest
                ORDER BY r.idRuta",
            "parametros"=>[
                ":orig"=>"%".$texto."%",
                ":dest"=>"%".$texto."%"
            ]
        ];
    }

    public function estadisticaPorRuta()
    {
        return [
            "sql" => "
                SELECT 
                    DATE_FORMAT(v.fechaHoraSalida, '%M') AS mes,
                    COUNT(*) AS cantidad
                FROM g2_vuelo v
                WHERE v.idRuta = :idRuta
                GROUP BY MONTH(v.fechaHoraSalida)
                ORDER BY MONTH(v.fechaHoraSalida)
            ",
            "parametros" => [
                ":idRuta" => $this->idRuta
            ]
        ];
    }
    
    public function obtenerRutas()
    {
        return [
            "sql" => "SELECT `idRuta`,`Duracion_Estimada`, `Distancia_KM`, `Origen`, `Destino` 
                    FROM `g2_ruta` ",
            "parametros" => []
        ];
    }
}