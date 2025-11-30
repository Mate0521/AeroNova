<?php

class Conexion {
    private $conexion;
    private $resultado;
    private $charset = "utf8";
    private $hostname;
    private $database;
    private $username;
    private $password;

    public function __construct()
    {
        // Obtener variables desde getenv(), que SÍ funciona en producción
        $this->hostname = getenv("DB_HOST") ?: "localhost";
        $this->database = getenv("DB_NAME") ?: "aeropuerto";
        $this->username = getenv("DB_USER") ?: "root";
        $this->password = getenv("DB_PASS") ?: "";
    }

    public function abrir() {
        try {

            // Localhost detectado por IP ::1
            $database = $this->database;
            $username = $this->username;
            $password = $this->password;
            

            // Validación obligatoria
            if (!$database || !$username) {
                throw new Exception("Variables de entorno no cargadas. DB o USER vacío.");
            }

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->conexion = new PDO(
                "mysql:host={$this->hostname};dbname={$database};charset={$this->charset}",
                $username,
                $password,
                $options
            );

        } catch (Exception $e) {
            // Mostrar error claro temporalmente
            die("❌ ERROR DE CONEXIÓN: " . $e->getMessage());
        }
    }

    public function cerrar() {
        $this->conexion = null;
    }

    public function ejecutar($sql, $parametros = []) {
        if ($this->conexion === null) {
            die("❌ ERROR: Conexión no abierta en ejecutar()");
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($parametros);
        $this->resultado = $stmt;
    }

    public function registro() {
        return $this->resultado->fetch();
    }

    public function filas() {
        return $this->resultado->rowCount();
    }

    public function lastID(){
        return $this->conexion->lastInsertId();
    }
}
