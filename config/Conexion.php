<?php

class Conexion{
    private $conexion;
    private $resultado;
    private $charset="utf8";
    private $hosname = "localhost";

    private $databadase = "aeropuerto";
    private $username = "root";
    private $password = "";

    // private $databadase = "itiud_aplint";
    // private $username = "itiud_aplint";
    // private $password = "GYesgQ118&";
    
    public function abrir(){
        if($_SERVER['REMOTE_ADDR'] == "::1"){
            $this -> conexion = new mysqli("localhost", "root", "", "aeropuerto");
        }else{
            $this -> conexion = new mysqli("localhost", "itiud_cocinaetilica", "UXpieQ728%", "itiud_cocinaetilica");
        }
    }
    
    public function cerrar(){
        $this -> conexion -> close();
    }
    
    public function ejecutar($sentencia){
        $this -> resultado = $this -> conexion -> query($sentencia);
    }
    
    public function registro(){
        return $this -> resultado -> fetch_row();
    }
    
    public function filas(){
        return $this -> resultado -> num_rows;
    }
    
}


?>