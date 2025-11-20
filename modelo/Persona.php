<?php
abstract class Persona
{
    protected $id;
    protected $nombre;
    protected $apellido;
    protected $correo;
    protected $telefono;
    protected $clave;
    //constructor
    public function __construct($id=null, $nombre=null, $apellido=null, $correo=null, $telefono=null, $clave=null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
    }
    //getters
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getCorreo()
    {
        return $this->correo;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getClave()
    {
        return $this->clave;
    }

    //setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setClave($clave)
    {
        $this->clave = $clave;
    }

}
