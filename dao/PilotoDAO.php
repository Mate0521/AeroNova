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
            "sql" => "SELECT  `Nombre`, `Apellido`, `Correo`, `Telefono`, `Foto`, `estado_cuenta`, `id_estado_piloto`,`Clave`
                    FROM `g2_piloto` 
                    WHERE `idPiloto`= :id",
            "parametros" => [
                ":id" => $this->id
                ]
        ];
    }

    public function autenticar(){
        return [
            "sql" => "select idPiloto
                from g2_piloto
                where Correo = :correo and Clave = :clave",
            "parametros" => [
                ":correo" => $this->correo,
                ":clave" => md5($this->clave)
            ]
        ];
    }
    public function actualizarClave() {
        return [
            "sql" => "UPDATE g2_piloto SET Clave = :clave WHERE idPiloto = :id",
            "parametros" => [
                ":clave" => md5($this->clave),
                ":id" => $this->id
            ]
        ];
    }

    public function actualizarPilotoInAir()
    {
        return [
            "sql"=>"UPDATE `g2_piloto` 
                SET `id_estado_piloto`= 2 
                WHERE `idPiloto` = :piloto",
            "parametros"=>[
                ":piloto"=>$this->id
            ]
        ];     
    }

    public function actualizarPilotoDisponible()
    {
        return [
            "sql" => "UPDATE `g2_piloto`
                    SET `id_estado_piloto` = 1
                    WHERE `idPiloto` = :piloto",
            "parametros" => [
                ":piloto" => $this->id
            ]
        ];
    }

}