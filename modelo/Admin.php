<?php
require_once(__DIR__ . "/../config/Conexion.php");
require_once(__DIR__ . "/../config/Seguridad.php");

require_once(__DIR__ . "/../dao/AdminDAO.php");
require_once(__DIR__ . "/Persona.php");

class Admin extends Persona {

    public function __construct($id=0, $nombre="", $apellido="", $correo="", $clave="",$telefono=""){
        parent::__construct($id, $nombre, $apellido, $correo, $clave,$telefono);
    }
    
    public function autenticar(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $adminDAO = new AdminDAO("", "", "", $this -> correo, $this -> clave);
        $conexion -> ejecutar($adminDAO -> autenticar());
        $tupla = $conexion -> registro();
        $conexion -> cerrar();
        if($tupla != null){
            $this -> id = $tupla[0];
            return true;
        }else{
            return false;
        }
    }
}



?>