<?php 
require_once (__DIR__."/../config/env.php");
require_once(__DIR__ . '/../dao/PilotoDAO.php');
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/Persona.php');
require_once(__DIR__ . '/Estado.php');

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
    $pilotoDAO = new PilotoDAO($this->id);
    try {
        $sql = $pilotoDAO->obtenerPilotoId();
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);
        if($fila = $conexion->registro()){
            $this->nombre       = $fila[0];
            $this->apellido     = $fila[1];
            $this->correo       = $fila[2];
            $this->telefono     = $fila[3];
            $this->foto         = $fila[4];
            $this->estadoCuenta = $fila[5];

            $estadoOB = new Estado($fila[6]);
            $estadoOB->obtenerEstadoPilotoId();
            $this->estadoPiloto = $estadoOB;

            // ⭐⭐ IMPORTANTE — CARGAR LA CLAVE ⭐⭐
            $this->clave = $fila[7];
        }
        $conexion->cerrar();
    } catch (Exception $e) {
        $conexion->cerrar();
        return $e;
    }
}

    public function obtenerPilotos() {
    $conexion = new Conexion();
    $conexion->abrir();
    $pilotoDAO = new PilotoDAO();
    $pilotos = [];

    try {
        $sql = $pilotoDAO->obtenerPilotos();
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);

        while ($fila = $conexion->registro()) {
            $estadoOB = new Estado($fila[6]);
            $estadoOB->obtenerEstadoPilotoId();

            $piloto = new Piloto(
                $fila[0], // ✅ idPiloto
    $fila[1], // nombre
    $fila[2], // apellido
    $fila[3], // correo
    $fila[4], // telefono
    $fila[8], // clave
    $fila[5], // foto
    $fila[6], // estadoCuenta
    $estadoOB // estadoPiloto             // estadoPiloto (objeto)
            );

            $pilotos[] = $piloto;
        }

        $conexion->cerrar();
        return $pilotos;

    } catch (Exception $e) {
        $conexion->cerrar();
        return $e;
    }
}




    public function autenticar(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $pilotoDAO = new PilotoDAO("", "", "", $this -> correo, "", $this -> clave);
        $sql = $pilotoDAO -> autenticar();
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
public function cambiarClave($actual, $nueva) {

    // Validar clave actual
    if (md5($actual) !== $this->getClave()) {
        return "incorrecta";
    }

    require_once(__DIR__ . '/../dao/PilotoDAO.php');
    require_once(__DIR__ . '/../config/Conexion.php');

    $conexion = new Conexion();
    $conexion->abrir();

    $pilotoDAO = new PilotoDAO($this->id, null, null, null, null, $nueva);

    $sql = $pilotoDAO->actualizarClave();
    $conexion->ejecutar($sql["sql"], $sql["parametros"]);
    $conexion->cerrar();

    return "ok";
}
public function consultarVuelosPorFiltros($idPiloto, $filtro)
{
    $sql = "SELECT v.idVuelo, v.Fecha, v.Hora_Despegue,
                   p1.nombre AS piloto_principal,
                   p2.nombre AS copiloto,
                   a.matricula AS avion_matricula,
                   a.modelo AS avion_modelo,
                   r.nombre AS ruta_nombre,
                   v.Hora_Llegada, v.Estado_Vuelo_idEstado_Vuelo
            FROM g2_vuelo v
            INNER JOIN g2_piloto p1 ON v.Piloto_principal = p1.idPiloto
            INNER JOIN g2_piloto p2 ON v.Copiloto = p2.idPiloto
            INNERJOIN g2_avion a ON v.Avion_Matricula = a.matricula
            INNER JOIN g2_ruta r ON v.Ruta_idRuta = r.idRuta
            WHERE v.Piloto_principal = ?";

    $param = [$idPiloto];

    // Si el filtro no está vacío, buscar por copiloto, avión o ruta
    if (!empty($filtro)) {
        $sql .= " AND (
                        p2.nombre LIKE ?
                        OR a.matricula LIKE ?
                        OR a.modelo LIKE ?
                        OR r.nombre LIKE ?
                    )";

        $like = "%$filtro%";
        $param[] = $like; // copiloto
        $param[] = $like; // matrícula
        $param[] = $like; // modelo
        $param[] = $like; // ruta
    }

    return ["sql" => $sql, "parametros" => $param];
}

    public function actualizarPilotoInAir()
    {
        $conexion = new Conexion();
        $conexion -> abrir();
        $pilotoDAO = new PilotoDAO($this->id);
        try {
            $sql=$pilotoDAO->actualizarPilotoInAir();
            $conexion->ejecutar($sql['sql'],$sql['parametros']);
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }

    }

    public function actualizarPilotoDisponible()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $pilotoDAO = new PilotoDAO($this->id);

        try {
            $sql = $pilotoDAO->actualizarPilotoDisponible();
            $conexion->ejecutar($sql['sql'], $sql['parametros']);
            $conexion->cerrar();

        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

public function buscar($filtro)
{
    $conexion = new Conexion();
    $conexion->abrir();

    $pilotoDAO = new PilotoDAO();
    $consulta = $pilotoDAO->buscar($filtro);

    $conexion->ejecutar($consulta["sql"], $consulta["parametros"]);

    $pilotos = [];

    while ($tupla = $conexion->registro()) {
        // $tupla[7] = id_estado_piloto
        // $tupla[8] = valor del estado

        $estadoObj = new Estado($tupla[7], $tupla[8]);

        $piloto = new Piloto(
            $tupla[0], // idPiloto
            $tupla[1], // nombre
            $tupla[2], // apellido
            $tupla[3], // correo
            $tupla[4], // telefono
            null,      // clave NO viene en esta consulta
            $tupla[5], // foto
            $tupla[6], // estado_cuenta
            $estadoObj // objeto Estado en vez de string
        );

        $pilotos[] = $piloto;
    }

    $conexion->cerrar();
    return $pilotos;
}


public function actualizarEstado($idPiloto, $idEstado)
{
    $conexion = new Conexion();
    $conexion->abrir();

    $pilotoDAO = new PilotoDAO();
    $sql = $pilotoDAO->actualizarEstado($idPiloto, $idEstado);

    try {
        $parametros = [$idEstado, $idPiloto];
        $conexion->ejecutar($sql["sql"], $parametros);

        $filas = $conexion->filas();

        $conexion->cerrar();

        return $filas > 0;

    } catch (Exception $e) {
        $conexion->cerrar();
        error_log("[Piloto->actualizarEstado] Error: " . $e->getMessage());
        return false;
    }
}
public function agregarPiloto() {
    $conexion = new Conexion();
    $conexion->abrir();

    $dao = new PilotoDAO(
    $this->id,
    $this->nombre,
    $this->apellido,
    $this->correo,
    $this->telefono,
    md5("123"),          // ← contraseña por defecto
    $this->foto,
    null,
    $this->estadoPiloto
);


    $sql = $dao->agregarPiloto();

    try {
        $conexion->ejecutar($sql["sql"], $sql["parametros"]);
        // Si no lanza excepción, asumimos éxito
        $conexion->cerrar();
        return true;
    } catch (Exception $e) {
        $conexion->cerrar();
        error_log("[Piloto->agregarPiloto] Error: " . $e->getMessage());
        return false;
    }
}


}