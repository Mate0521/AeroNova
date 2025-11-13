<?php

class Conexion
{
    private $conexion;
    private $resultado;
    private $charset="utf8";
    private $hosname = "localhost";

    // private $databadase = "liga_main";
    // private $username = "root";
    // private $password = "";

    private $databadase = "itiud_aplint";
    private $username = "itiud_aplint";
    private $password = "GYesgQ118&";
    
    function abrir(){
        try{
            $option = [
                PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conexion = new PDO("mysql:host={$this->hosname};dbname={$this->databadase};charset={$this->charset}", 
                                    $this->username, 
                                    $this->password,
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



}