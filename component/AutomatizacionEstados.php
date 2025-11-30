<?php
class AutomatizacionEstados {

    public static function run() {

        $file = __DIR__ . "/last-cron.txt";

        if (!file_exists($file)) {
            file_put_contents($file, "2000-01-01 00:00:00");
        }

        $ultima = file_get_contents($file);
        $haceMinutos = (time() - strtotime($ultima)) / 60;

        if ($haceMinutos >= 1) {

            file_put_contents($file, date("Y-m-d H:i:s"));

            self::activarCheckIn();
            self::cerrarCheckIn();
            self::procesarAbordaje();
            self::finalizarVuelos();

        }
    }

    private static function activarCheckIn() 
    {
        $ticket=new Ticket();
        $ticket->actualizacionCheckinOpen();

    }
    private static function cerrarCheckIn() 
    {
        $ticket=new Ticket();
        $ticket->actualizacionCheckinClose();
    }
    private static function procesarAbordaje() 
    {
        $vuelo=new Vuelo;
        $vuelos=$vuelo->consultarVuelosProcesoAbordaje();
        foreach($vuelos as $v){

            $v->actualizarVueloInAir();

            $piloto =new Piloto($v->getPilotoPrincipal()->getId());
            $piloto->actualizarPilotoInAir();

            $coPiloto=new Piloto($v->getCopiloto()->getId());
            $coPiloto->actualizarPilotoInAir();

            //actualizar tikets
            $ticket = new Ticket("","","","","",$v->getIdVuelo());
            $ticket->actualizarTiketsInAir();

        }

    }
    private static function finalizarVuelos()
    {
        $vuelo = new Vuelo();
        $vuelos = $vuelo->consultarVuelosFinalizar();

        foreach ($vuelos as $v) {

            $v->actualizarVueloFinalizado();

            $piloto = new Piloto($v->getPilotoPrincipal()->getId());
            $piloto->actualizarPilotoDisponible();

            $copiloto = new Piloto($v->getCopiloto()->getId());
            $copiloto->actualizarPilotoDisponible();

        }
    }


}

?>

