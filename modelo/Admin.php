<?php 

require_once(__DIR__ . '/../dao/AdminDAO.php');
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/Persona.php');



class Admin extends Persona{
    
    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null){
        parent::__construct($id, $nombre, $apellido, $correo, $telefono, $clave);
    }

    public function obtenerAdminId(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $adminDAO = new AdminDAO($this->id, null, null, null, null, $this->clave);
        try {
            $sql =$adminDAO -> obtenerAdminId();
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            if($fila = $conexion ->registro()){
                $this->nombre = $fila[0];
                $this->apellido = $fila[1];
                $this->correo = $fila[2];
                $this->telefono = $fila[3];
                $this->clave    = $fila[4];
            }
            $conexion -> cerrar();
        } catch (Exception $e) {
            $conexion -> cerrar();
            return $e;
        }
    }
    
    public function autenticar(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $adminDAO = new AdminDAO("", "", "", $this -> correo, "", $this -> clave);
        $sql = $adminDAO -> autenticar();
        $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
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
