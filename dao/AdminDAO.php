<?php
class AdminDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $telefono;

    public function __construct($id=0, $nombre="", $apellido="", $correo="", $clave="",$telefono=""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> telefono = $telefono;

    }
    
    public function autenticar(){
        return "select idAdministrador
                from administrador
                where Correo = '" . $this -> correo . "' and Clave = md5('" . $this -> clave . "')";
    }
    
    
}

