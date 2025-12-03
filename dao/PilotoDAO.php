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

<<<<<<< HEAD
    public function autenticar(){
=======
<<<<<<< Updated upstream
        public function autenticar(){
=======
        public function obtenerPilotos(){
        return [
            "sql" => "SELECT `idPiloto`, `Nombre`, `Apellido`, `Correo`, `Telefono`, `Foto`, `estado_cuenta`, `id_estado_piloto`, `Clave`
                    FROM `g2_piloto`",
            "parametros" => []
        ];
    }

    public function autenticar(){
>>>>>>> Stashed changes
>>>>>>> feature/Natalia
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

<<<<<<< HEAD
=======
<<<<<<< Updated upstream
=======
>>>>>>> feature/Natalia
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
<<<<<<< HEAD
=======
public function buscar($filtro)
{
    return [
        "sql" =>
        "SELECT 
            p.idPiloto,
            p.Nombre,
            p.Apellido,
            p.Correo,
            p.Telefono,
            p.Foto,
            p.estado_cuenta,
            p.id_estado_piloto,
            e.valor AS estado
        FROM g2_piloto p
        INNER JOIN g2_estado_piloto e 
            ON p.id_estado_piloto = e.id_estado
        WHERE p.Nombre LIKE ?
           OR p.Apellido LIKE ?
           OR p.Correo LIKE ?
           OR p.Telefono LIKE ?
           OR e.valor LIKE ?
        ",
        "parametros" => [
            "%$filtro%",
            "%$filtro%",
            "%$filtro%",
            "%$filtro%",
            "%$filtro%"
        ]
    ];
}

public function actualizarEstado($idPiloto, $idEstado)
{
    return [
        "sql" => "UPDATE g2_piloto 
                 SET id_estado_piloto = ? 
                 WHERE idPiloto = ?",
        "parametros" => [$idEstado, $idPiloto] 
    ];
}
public function agregarPiloto() {
    return [
        "sql" => "INSERT INTO g2_piloto 
                    (idPiloto, Nombre, Apellido, Correo, Telefono, Foto, id_estado_piloto)
                  VALUES 
                    (:idPiloto, :Nombre, :Apellido, :Correo, :Telefono, :Foto, :id_estado_piloto)",
        "parametros" => [
            ":idPiloto"         => $this->id,
            ":Nombre"           => $this->nombre,
            ":Apellido"         => $this->apellido, 
            ":Correo"           => $this->correo,
            ":Telefono"         => $this->telefono,
            ":Foto"             => $this->foto,
            ":id_estado_piloto" => $this->estadoPiloto,
        ]
    ];
}

>>>>>>> Stashed changes
>>>>>>> feature/Natalia

}