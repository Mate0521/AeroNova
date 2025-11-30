<?php

class Conexion{
    private $conexion;
    private $resultado;
    private $charset="utf8";
    private $hosname;
    private $database;
    private $username;
    private $password;

    public function __construct()
    {
        $this->hosname = $_ENV["DB_HOST"] ?? "localhost";
        $this->database = $_ENV["DB_NAME"] ?? "";
        $this->username = $_ENV["DB_USER"] ?? "";
        $this->password = $_ENV["DB_PASS"] ?? "";
    }

    function abrir(){
        try{
            if ($_SERVER['REMOTE_ADDR'] == "::1") {
                $databadase = "aeropuerto";
                $username = "root";
                $password = "";
            } else {
                $databadase = $this->database;
                $username = $this->username;
                $password = $this->password;
            }
            var_dump($databadase, $username, $password);

            $option = [
                PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conexion = new PDO("mysql:host={$this->hosname};dbname={$databadase};charset={$this->charset}", 
                                    $username, 
                                    $password,
                                    $option);
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    public function cerrar() {
        $this->conexion = null; 
    }
    public function ejecutar($sql, $parametros = []) {
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($parametros);
        $this->resultado = $stmt;
    }
    public function registro() {
        return $this->resultado->fetch();
    }

    public function filas() {
        return $this->resultado->rowCount();
    }

    public function lastID(){
        return $this->conexion->lastInsertId();
    }

}


?>