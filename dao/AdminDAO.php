<?php
class AdminDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $clave;

    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
    }

    public function obtenerAdminId(){
        return [
            "sql" => "SELECT  `Nombre`, `Apellido`, `Correo`, `Telefono` 
                    FROM `g2_administrador` 
                    WHERE `idAdministrador`= :id",
            "parametros" => [
                ":id" => $this->id
                ]
        ];
    }
    
    public function autenticar(){
        return [
            "sql" => "select idAdministrador
                from g2_administrador
                where Correo = :correo and Clave = :clave",
            "parametros" => [
                ":correo" => $this->correo,
                ":clave" => md5($this->clave)
            ]
        ];
    }
    
    
}

