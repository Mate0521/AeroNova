<?php 
class PilotoDAO{

    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $clave;
    private $estadoPiloto;

    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null, $estadoPiloto = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->estadoPiloto = $estadoPiloto;
    }

    public function obtenerPilotoId(){
        return [
            "sql" => "SELECT  `Nombre`, `Apellido`, `Correo`, `Telefono`, `EstadoPiloto` 
                    FROM `piloto` 
                    WHERE `idPiloto`= :id",
            "parametros" => [
                ":id" => $this->id
                ]
        ];
    }
}