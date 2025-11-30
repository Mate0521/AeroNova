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
        // fallback a getenv()
        $this->hostname = getenv("DB_HOST") ?: "localhost";
        $this->database = getenv("DB_NAME") ?: "";
        $this->username = getenv("DB_USER") ?: "";
        $this->password = getenv("DB_PASS") ?: "";
    }

    public function abrir() {
        // Si ya está abierta no volver a abrir
        if ($this->conexion instanceof PDO) return;

        try {
            // Detectar entorno local de forma fiable (opcional)
            $esLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);

            if ($esLocal && empty($this->database)) {
                $database = "aeropuerto";
                $username = "root";
                $password = "";
            } else {
                $database = $this->database;
                $username = $this->username;
                $password = $this->password;
            }

            if (empty($database) || empty($username)) {
                throw new Exception("Credenciales DB incompletas. Host: {$this->hostname}, DB: {$database}, User: {$username}");
            }

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->conexion = new PDO(
                "mysql:host={$this->hostname};dbname={$database};charset={$this->charset}",
                $username,
                $password,
                $options
            );

        } catch (Exception $e) {
            // Registrar error y re-lanzar para que el caller lo capture
            error_log("[Conexion->abrir] " . $e->getMessage());
            throw $e;
        }
    }

    public function cerrar() {
        $this->conexion = null;
    }

    public function ejecutar($sql, $parametros = []) {
        // Si la conexión no está abierta, intentamos abrirla
        if ($this->conexion === null) {
            try {
                $this->abrir();
            } catch (Exception $e) {
                // enviar respuesta JSON amigable si es AJAX o lanzar
                error_log("[Conexion->ejecutar] fallo al abrir conexion: " . $e->getMessage());
                throw $e;
            }
        }

        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($parametros);
            $this->resultado = $stmt;
        } catch (PDOException $pe) {
            error_log("[Conexion->ejecutar] SQL error: " . $pe->getMessage() . " -- SQL: " . $sql);
            throw $pe;
        }
    }

    public function registro() {
        return $this->resultado ? $this->resultado->fetch(PDO::FETCH_NUM) : false;
    }

    public function filas() {
        return $this->resultado ? $this->resultado->rowCount() : 0;
    }

    public function lastID(){
        return $this->conexion ? $this->conexion->lastInsertId() : null;
    }
}
