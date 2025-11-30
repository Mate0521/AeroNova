<?php 
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/Persona.php'); 
require_once(__DIR__ . '/../dao/PasajeroDAO.php');
require_once (__DIR__."/../config/.env.php");

class Pasajero extends Persona{
    private $codigoVerificacion;
    private $estado_cuenta;

    //constructor
    public function __construct($id=null, $nombre=null, $apellido=null, $correo=null, $telefono=null, $clave=null, $codigoVerificacion = null, $estado_cuenta = null)
    {
        parent::__construct($id,$nombre, $apellido, $correo, $telefono, $clave);
        $this->codigoVerificacion = $codigoVerificacion;
        $this->estado_cuenta = $estado_cuenta;
    }
    //getter
    public function getCodigoVerificacion()
    {
        return $this->codigoVerificacion;
    }
    public function getEstadoCuenta()
    {
        return $this->estado_cuenta;
    }
    //setter
    public function setCodigoVerificacion($codigoVerificacion)
    {
        $this->codigoVerificacion = $codigoVerificacion;
    }
    public function setEstadoCuenta($estado_cuenta)
    {
        $this->estado_cuenta = $estado_cuenta;
    }

    public function crearPasajero(){
        $this->codigoVerificacion = $this->crearCodigoVerificacion();
        $conexion = new Conexion();
        $conexion -> abrir();
        $pasajeroDAO = new PasajeroDAO(null, $this->nombre, $this->apellido, $this->correo, $this->telefono, $this->clave, $this->codigoVerificacion);
        try {
            $sql =$pasajeroDAO -> crearPasajero();
            var_dump($sql);
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            $conexion -> cerrar();

            // $asunto = "Regitro de cliente";
            // $mensaje = "Hola " . $this->nombre ." ". $this->apellido. "\n\r";
            // $mensaje .= "Debe activar su cuenta haciendo clic en: \n\r";
            // $mensaje .= "http://p2.itiud.org/?pid=" . base64_encode("Activar") . "&c=" . base64_encode($this->correo);
            // $opciones = array(
            //     "From" => "contacto@itiud.org",
            //     "Reply-To" => "no-responder@itiud.org"
            // );
            
            // mail($this->correo, $asunto, $mensaje, $opciones);


            return true;
        } catch (Exception $e) {
            $conexion -> cerrar();
            throw $e;
        }
        
    }
    public function crearCodigoVerificacion(){
        return rand(100000, 999999);
    }

    public function consultaPorCorreo(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $pasajeroDAO = new PasajeroDAO(null, null, null, $this->correo, null, null, null);
        try {
            $sql =$pasajeroDAO -> consultarPorCorreo();
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            if($fila = $conexion ->registro()){
                $this->id = $fila[0];
                $this->nombre = $fila[1];
                $this->apellido = $fila[2];
                $this->telefono = $fila[3];
                $this->codigoVerificacion = $fila[4];
            }
            $conexion -> cerrar();
            return true;
        } catch (Exception $e) {
            $conexion -> cerrar();
            return $e;
        }
    }
    public function varificarCodigoVerificacion($codigoVerificacion){
        if($this->codigoVerificacion == $codigoVerificacion){
            return true;
        } else {
            return false;
        }
    }
    public function activarCuenta(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $pasajeroDAO = new PasajeroDAO($this->id, null, null, null, null, null, null);
        $sql = $pasajeroDAO -> activarCuenta();
        try {
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            $conexion -> cerrar();
        } catch (Exception $e) {
            $conexion -> cerrar();
            throw $e;
        }
    }

    public function obtenerPasajeroId(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $pasajeroDAO = new PasajeroDAO($this->id, null, null, null, null, null, null);
        try {
            $sql =$pasajeroDAO -> obtenerPasajeroId();
            $conexion -> ejecutar($sql["sql"], $sql["parametros"]);
            if($fila = $conexion ->registro()){
                $this->nombre = $fila[0];
                $this->apellido = $fila[1];
                $this->correo = $fila[2];
                $this->telefono = $fila[3];
                $this->estado_cuenta = $fila[4];
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
        $pasajeroDAO = new PasajeroDAO("", "", "", $this -> correo, "", $this -> clave);
        $sql = $pasajeroDAO -> autenticar();
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