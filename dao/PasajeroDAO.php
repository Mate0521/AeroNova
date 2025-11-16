<?php 
class PasajeroDAO
{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $clave;
    private $codigoVerificacion;

    //constructor
    public function __construct($id=null, $nombre=null, $apellido=null, $correo=null, $telefono=null, $clave=null, $codigoVerificacion = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->codigoVerificacion = $codigoVerificacion;
    }

    public function crearPasajero(){
        return [
            "sql"=>"INSERT INTO `pasajero`( `Nombre`, `Apellido`, `Correo`, `Telefono`, `Clave`, `Codigo_Verificacion`)
                VALUES ( :nombre, :apellido, :correo, :telefono, :clave, :codigoVerificacion )",
            "parametros"=>[
                ":nombre"=>$this->nombre,
                ":apellido"=>$this->apellido,
                ":correo"=>$this->correo,
                ":telefono"=>$this->telefono,
                ":clave"=>md5($this->clave),
                ":codigoVerificacion"=>$this->codigoVerificacion
            ]
            ];
    }
    public function consultarPorCorreo(){
        return [
            "sql"=>"SELECT `idPasajero`, `Nombre`, `Apellido`, `Telefono`, `Codigo_Verificacion` 
                FROM `pasajero` 
                WHERE `Correo`= :correo",
            "parametros"=>[
                ":correo"=>$this->correo
            ]
            ];
    }
    public function activarCuenta(){
        return [
            "sql"=>"UPDATE `pasajero` 
                SET `estado`= 1, `Codigo_Verificacion`= 0
                WHERE `idPasajero`= :idPasajero",
            "parametros"=>[
                ":idPasajero"=>$this->id
            ]
        ];
    }
    public function obtenerPasajeroId(){
        return [
            "sql" => "SELECT  `Nombre`, `Apellido`, `Correo`, `Telefono` 
                    FROM `pasajero` 
                    WHERE `idPasajero`= :id",
            "parametros" => [
                ":id" => $this->id
                ]
        ];
    }
}
?>