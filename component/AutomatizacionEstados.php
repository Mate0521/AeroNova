<?php
class PseudoCron {

    public static function run() {

        // archivo que guarda la última ejecución
        $file = __DIR__ . "/last-cron.txt";

        // si el archivo no existe lo creamos
        if (!file_exists($file)) {
            file_put_contents($file, "2000-01-01 00:00:00");
        }

        $ultima = file_get_contents($file);
        $haceMinutos = (time() - strtotime($ultima)) / 60;

        // ejecutar cada 5 minutos
        if ($haceMinutos >= 5) {

            // ACTUALIZAMOS INMEDIATAMENTE LA HORA
            file_put_contents($file, date("Y-m-d H:i:s"));

            // EJECUTAMOS TODAS LAS TAREAS
            self::activarCheckIn();
            self::cerrarCheckIn();
            self::procesarAbordaje();
            self::finalizarVuelos();

        }
    }

    // Aquí dentro irán todas las funciones con el SQL
    private static function activarCheckIn() 
    {
        
    }
    private static function cerrarCheckIn() {}
    private static function procesarAbordaje() {}
    private static function finalizarVuelos() {}

}

?>

