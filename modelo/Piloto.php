<?php 
class Piloto extends Persona{
    
    private $estadoPiloto;
    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null, $estadoPiloto = null){
        parent::__construct($id, $nombre, $apellido, $correo, $telefono, $clave);
        $this->estadoPiloto = $estadoPiloto;
    }
    public function getEstadoPiloto(){
        return $this->estadoPiloto;
    }
    public function setEstadoPiloto($estadoPiloto){
        $this->estadoPiloto = $estadoPiloto;
    }

    public function obtenerPilotoId(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $pilotoDAO = new PilotoDAO($this->id, null, null, null, null, null, null);
        try {
            $sql =$pilotoDAO -> obtenerPilotoId();
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            if($fila = $conexion ->registro()){
                $this->nombre = $fila[0];
                $this->apellido = $fila[1];
                $this->correo = $fila[2];
                $this->telefono = $fila[3];
                $this->estadoPiloto = $fila[4];
            }
            $conexion -> cerrar();
        } catch (Exception $e) {
            $conexion -> cerrar();
            return $e;
        }
    }


}