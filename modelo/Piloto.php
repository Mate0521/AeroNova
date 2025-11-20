<?php 
class Piloto extends Persona{
    
    private $estadoPiloto;
    private $foto;
    private $estadoCuenta;

    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null, $foto = null, $estadoCuenta = null, $estadoPiloto = null){
        parent::__construct($id, $nombre, $apellido, $correo, $telefono, $clave);
        $this->estadoPiloto = $estadoPiloto;
        $this->foto = $foto;
        $this->estadoCuenta = $estadoCuenta;
    }
    public function getEstadoPiloto(){
        return $this->estadoPiloto;
    }
    public function getFoto(){
        return $this->foto;
    }
    public function getEstadoCuenta(){
        return $this->estadoCuenta;
    }
    public function setEstadoPiloto($estadoPiloto){
        $this->estadoPiloto = $estadoPiloto;
    }
    public function setFoto($foto){
        $this->foto = $foto;
    }
    public function setEstadoCuenta($estadoCuenta){
        $this->estadoCuenta = $estadoCuenta;
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
                $this->foto = $fila[4];
                $this->estadoCuenta = $fila[5];

                $estadoOB = new Estado($fila[6]);
                $estadoOB->obtenerEstadoPilotoId();
                $this->estadoPiloto = $estadoOB;
            }
            $conexion -> cerrar();
        } catch (Exception $e) {
            $conexion -> cerrar();
            return $e;
        }
    }


}