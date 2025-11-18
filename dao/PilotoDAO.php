<?php 
class PilotoDAO{

    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $clave;
    private $estadoPiloto;
    private $foto;
    private $estadoCuenta;

    public function __construct($id = null, $nombre = null, $apellido = null, $correo = null, $telefono = null, $clave = null, $foto = null, $estadoCuenta = null, $estadoPiloto = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->foto = $foto;
        $this->estadoCuenta = $estadoCuenta;
        $this->estadoPiloto = $estadoPiloto;
    }

    public function obtenerPilotoId(){
        return [
            "sql" => "SELECT  `Nombre`, `Apellido`, `Correo`, `Telefono`, `Foto`, `estado_cuenta`, `id_estado_piloto`
                    FROM `piloto` 
                    WHERE `idPiloto`= :id",
            "parametros" => [
                ":id" => $this->id
                ]
        ];
    }
}