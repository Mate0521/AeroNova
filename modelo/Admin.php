<?php 
class Admin extends Persona{
    
    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null){
        parent::__construct($id, $nombre, $apellido, $correo, $telefono, $clave);
    }

    public function obtenerAdminId(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $adminDAO = new AdminDAO($this->id, null, null, null, null, null);
        try {
            $sql =$adminDAO -> obtenerAdminId();
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            if($fila = $conexion ->registro()){
                $this->nombre = $fila[0];
                $this->apellido = $fila[1];
                $this->correo = $fila[2];
                $this->telefono = $fila[3];
            }
            $conexion -> cerrar();
        } catch (Exception $e) {
            $conexion -> cerrar();
            return $e;
        }
    }


}