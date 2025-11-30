<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../dao/TicketDAO.php');
require_once (__DIR__."/../config/env.php");

class Ticket
{
    private $idTicket;
    private $estado_ticket;//ob estado, consulta cascada
    private $precio;
    private $puesto;
    private $pasajero;//ob pasajero, consulta cascada
    private $vuelo;//ob vuelo, consulta cascada
    private $check_in;

    // Constructor
    public function __construct($idTicket = null, $estado_ticket = null, $precio = null, $puesto = null, $pasajero = null, $vuelo = null, $check_in = null)
    {
        $this->idTicket = $idTicket;
        $this->estado_ticket = $estado_ticket;
        $this->precio = $precio;
        $this->puesto = $puesto;
        $this->pasajero = $pasajero;
        $this->vuelo = $vuelo;
        $this->check_in = $check_in;
    }
    // Getters
    public function getIdTicket()
    {
        return $this->idTicket;
    }
    public function getEstadoTicket()
    {
        return $this->estado_ticket;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getPuesto()
    {
        return $this->puesto;
    }
    public function getPasajero()
    {
        return $this->pasajero;
    }
    public function getVuelo()
    {
        return $this->vuelo;
    }
    public function getCheckIn()
    {
        return $this->check_in;
    }
    //
    // Setters
    public function setEstadoTicket($estado_ticket)
    {
        $this->estado_ticket = $estado_ticket;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;
    }
    public function setPasajero($pasajero)
    {
        $this->pasajero = $pasajero;
    }
    public function setVuelo($vuelo)
    {
        $this->vuelo = $vuelo;
    }
    public function setCheckIn($check_in)
    {
        $this->check_in = $check_in;
    }

    public function obtenerTicketId()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO($this->idTicket, null, null, null, null, null, null);
        try {
            $sql = $ticketDAO->obtenerTicketId();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            if ($fila = $conexion->registro()) {

                $estadoOB = new Estado($fila[0]);
                $estadoOB->obtenerEstadoTicketId();
                $this->estado_ticket = $estadoOB;

                $this->precio = $fila[1];
                $this->puesto = $fila[2];

                $pasajeroOB = new Pasajero($fila[3]);
                $pasajeroOB->obtenerPasajeroId();
                $this->pasajero = $pasajeroOB;

                $vueloOB = new Vuelo($fila[4]);
                $vueloOB->obtenerVueloId();
                $this->vuelo = $vueloOB;

                $this->check_in = $fila[5];
            }
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function calcularPrecioBase()
    {
        $oilPriceKey = $_ENV["OILPRICE_KEY"];

        $url = 'https://api.oilpriceapi.com/v1/prices/latest';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Token ".$oilPriceKey
        ]);

        $datos = curl_exec($curl);

        $productos = json_decode($datos, true);

        // 3. Validar estructura
        if (
            !is_array($productos) || 
            !isset($productos["data"]) ||
            !isset($productos["data"]["price"])
        ) {

            $precioBrent = 85.0; 
        } else {
            $precioBrent = $productos["data"]["price"];
        }

        $vuelo = new Vuelo($this->vuelo);
        $vuelo->obtenerVueloId();
        $duracion = $vuelo->getRuta()->convertirTimeAHoras();

        $costoCombustible = (($precioBrent / 159 * 0.85) + 0.20) * 4 * $duracion;
        $costoOperacion  = 180 * $duracion;

        $precioBase = ($costoCombustible + $costoOperacion) * 1.30;

        $ocupacion = $vuelo->calcularOcupacion($this->cantidadParaVuelo());

        switch (true) {
            case ($ocupacion > 90):
                $factor = 1.80;
                break;
            case ($ocupacion > 60):
                $factor = 1.45;
                break;
            case ($ocupacion > 30):
                $factor = 1.15;
                break;
            default:
                $factor = 0.90;
        }

        return $precioBase * $factor;
    }

    public function cantidadParaVuelo()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO("", "", "", "", "", $this->vuelo);
        try {
            $sql = $ticketDAO->cantidadParaVuelo();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $cantidad = $conexion->registro();
            $conexion->cerrar();
            return $cantidad[0];
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function crearTicket()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO("", $this->estado_ticket, $this->precio, $this->puesto, $this->pasajero, $this->vuelo);
        try {
            $sql = $ticketDAO->crearTicket();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            $this->idTicket=$conexion->lastID();
            $conexion->cerrar();
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function actualizacionCheckinOpen()
    {
        
        $vuelo=new Vuelo();
        $vuelos=$vuelo->consultarVuelos();
        $vuelosProximos = [];
        $ahora = new DateTime(); 

        foreach ($vuelos as $v) {
            $fechaHoraVuelo = new DateTime($v->getFecha() . ' ' . $v->getHoraDespegue());
            $horasRestantes = ($fechaHoraVuelo->getTimestamp() - $ahora->getTimestamp()) / 3600;
            if ($horasRestantes <= 24 && $horasRestantes > 2) {
                $vuelosProximos[] = $v;
            }
        }

        try{
            $conexion = new Conexion();
            $conexion->abrir();
            $ticketDAO = new TicketDAO();
            $ticketActualizar=[];
            foreach($vuelosProximos as $v){
                $sql=$ticketDAO->obtenerTicketIdVuelo($v->getIdVuelo());
                $conexion->ejecutar($sql['sql'], $sql['parametros']);
                while($fila=$conexion->registro()){
                    $ticket=new Ticket($fila[0]);

                    $pasajero=new Pasajero($fila[1]);
                    $pasajero->obtenerPasajeroId();

                    $ticket->setPasajero($pasajero);

                    $ticketActualizar[]=$ticket;
                }
            }

            $conexion->ejecutar("BEGIN");

            foreach($ticketActualizar as $ticket){
                $sql=$ticketDAO->actualizarCheckinOpen($ticket->getIdTicket());
                $conexion->ejecutar($sql['sql'], $sql['parametros']);

                $asunto = "Activacion de Check-in";
                $mensaje = "Hola " . $ticket->getPasajero()->getNombre() ." ". $ticket->getPasajero()->getApellido() . "\n\r";
                $mensaje .= "Debe de realizar el proceso de check-in para obtener su pase de abordaje: \n\r";
                $mensaje .= "http://p2.itiud.org/?pid=" . base64_encode("passBoard") ."&idT=". base64_encode($ticket->getIdTicket());
                $opciones = array(
                    "From" => "contacto@itiud.org",
                    "Reply-To" => "no-responder@itiud.org"
                );
                
                mail($ticket->getPasajero()->getcorreo(), $asunto, $mensaje, $opciones);
            }

            $conexion->ejecutar("COMMIT");
            $conexion->cerrar();

        }catch (Exception $e){
            $conexion->ejecutar("ROLLBACK");
            $conexion->cerrar();
            return $e;
        }
    }

    public function actualizacionCheckinClose()
    {
        $vuelo = new Vuelo();
        $vuelos = $vuelo->consultarVuelos();
        $ahora = new DateTime();

        $vuelosCerrar = [];

        // Detectar vuelos cuya hora está entre 0 y 2 horas
        foreach ($vuelos as $v) {
            $fechaHoraVuelo = new DateTime($v->getFecha() . ' ' . $v->getHoraDespegue());
            $horasRestantes = ($fechaHoraVuelo->getTimestamp() - $ahora->getTimestamp()) / 3600;

            if ($horasRestantes <= 2 && $horasRestantes > 0) {
                $vuelosCerrar[] = $v;
            }
        }

        try {
            $conexion = new Conexion();
            $conexion->abrir();
            $ticketDAO = new TicketDAO();

            $conexion->ejecutar("BEGIN");

            foreach ($vuelosCerrar as $v) {

                // Obtener tickets del vuelo
                $sql = $ticketDAO->obtenerTicketIdVuelo($v->getIdVuelo());
                $conexion->ejecutar($sql["sql"], $sql["parametros"]);

                while ($fila = $conexion->registro()) {
                    $idTicket = $fila[0];
                    $idPasajero = $fila[1];

                    // Cargar pasajero
                    $pasajero = new Pasajero($idPasajero);
                    $pasajero->obtenerPasajeroId();

                    // Cambiar estado del ticket → 3 = check-in cerrado
                    $sqlUpdate = $ticketDAO->actualizarCheckinClose($idTicket);
                    $conexion->ejecutar($sqlUpdate["sql"], $sqlUpdate["parametros"]);

                    // Enviar correo
                    $asunto = "Cierre de Check-in";
                    $mensaje = "Hola " . $pasajero->getNombre() . " " . $pasajero->getApellido() . "\n\r";
                    $mensaje .= "El proceso de check-in para su vuelo ha cerrado.\n\r";
                    $mensaje .= "Si realizó el check-in podrá obtener su pase de abordaje.\n\r";
                    $mensaje .= "http://p2.itiud.org/?pid=" . base64_encode("passBoard") .
                                "&c=" . base64_encode($pasajero->getCorreo()) .
                                "&idT=" . base64_encode($idTicket);

                    $opciones= [
                        "From" => "contacto@itiud.org",
                        "Reply-To" => "no-responder@itiud.org"
                    ];

                    mail($pasajero->getCorreo(), $asunto, $mensaje, $opciones);
                }
            }

            $conexion->ejecutar("COMMIT");
            $conexion->cerrar();

        } catch (Exception $e) {
            $conexion->ejecutar("ROLLBACK");
            $conexion->cerrar();
            return $e;
        }
    }

    public function actualizarTiketsInAir()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO("","","","","",$this->vuelo);
        try {
            $sql=$ticketDAO->consutarCheckin();
            $conexion->ejecutar($sql['sql'],$sql['parametros']);
            while($fila=$conexion->registro()){
                if ($fila[1]==1) {
                    $sql=$ticketDAO->actualizarTiketsInAirP($fila[0]);
                }else{
                    $sql=$ticketDAO->actualizarTiketsInAirN($fila[0]);
                }
                $conexion->ejecutar($sql['sql'], $sql['parametros']);
            }
            $conexion->cerrar();
            
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function cambiarEstadoCheckin() 
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO($this->idTicket);
        try {
            $sql=$ticketDAO->cambiarEstadoCheckin();
            $conexion->ejecutar($sql['sql'],$sql['parametros']);
            $conexion->cerrar();

            $pdfFile=$this->generarBoardingPassPDF();

            $asunto = "AeroNova - Su Ticket #{$this->idTicket}";
            $mensaje = "Hola {$this->pasajero->getNombre()} {$this->pasajero->getApellido()}\n\n";
            $mensaje .= "Gracias por su compra. Adjunto encontrará su ticket de vuelo.\n";
            $mensaje .= "¡Buen viaje!\n\nAeroNova Airlines";

            // Encabezados para adjunto MIME
            $boundary = md5(time());
            $headers  = "From: AeroNova <no-reply@aeronova.com>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

            $body  = "--{$boundary}\r\n";
            $body .= "Content-Type: text/plain; charset=utf-8\r\n";
            $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $body .= $mensaje . "\r\n\r\n";

            // Adjuntar PDF
            $fileContent = chunk_split(base64_encode(file_get_contents($pdfFile)));
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Type: application/pdf; name=\"ticket_{$this->idTicket}.pdf\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"ticket_{$this->idTicket}.pdf\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $fileContent . "\r\n";
            $body .= "--{$boundary}--";

            mail($this->pasajero->getCorreo(), $asunto, $body, $headers);
            

        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
        
    }


    public function generarBoardingPassPDF()
    {
        $rutaCarpeta = __DIR__ . "/../passboards/";


        // Nombre del archivo
        $archivo = $rutaCarpeta . "passboard_".$this->idTicket . ".pdf";

        // Objetos asociados
        $pasajero = $this->pasajero;
        $vuelo = $this->vuelo;
        $ruta = $vuelo->getRuta();

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 18);

        // Título
        $pdf->Cell(0, 10, "BOARDING PASS", 0, 1, "C");
        $pdf->Ln(5);

        $pdf->SetFont("Arial", "", 12);

        // Info pasajero
        $pdf->Cell(50, 7, "Pasajero:", 0, 0);
        $pdf->Cell(100, 7, $pasajero->getNombre() . " " . $pasajero->getApellido(), 0, 1);

        // Ticket
        $pdf->Cell(50, 7, "ID Ticket:", 0, 0);
        $pdf->Cell(100, 7, $this->idTicket, 0, 1);

        // Vuelo
        $pdf->Cell(50, 7, "Vuelo ID:", 0, 0);
        $pdf->Cell(100, 7, $vuelo->getIdVuelo(), 0, 1);

        $pdf->Cell(50, 7, "Origen:", 0, 0);
        $pdf->Cell(100, 7, $ruta->getOrigen()->getNombre(), 0, 1);

        $pdf->Cell(50, 7, "Destino:", 0, 0);
        $pdf->Cell(100, 7, $ruta->getDestino()->getNombre(), 0, 1);

        $pdf->Cell(50, 7, "Fecha:", 0, 0);
        $pdf->Cell(100, 7, $vuelo->getFecha(), 0, 1);

        $pdf->Cell(50, 7, "Hora Despegue:", 0, 0);
        $pdf->Cell(100, 7, $vuelo->getHoraDespegue(), 0, 1);

        $pdf->Cell(50, 7, "Puesto:", 0, 0);
        $pdf->Cell(100, 7, $this->puesto, 0, 1);

        $pdf->Ln(10);

        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(0, 10, "Presentar este documento en la puerta de embarque", 0, 1, "C");

        // Guardar en carpeta local
        $pdf->Output("F", $archivo);

        return $archivo;
    }

    public function obtenerTicketsPasajero()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO(null, null, null, null, $this->pasajero);

        $tickets = [];

        try {
            $sql = $ticketDAO->obtenerTicketsPasajero();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);
            while ($fila = $conexion->registro()) {

                $t =new Ticket($fila[0]);

                $estadoOB = new Estado($fila[1]);
                $estadoOB->obtenerEstadoTicketId();
                $t->setEstadoTicket($estadoOB);

                $t->setPrecio( $fila[2]);
                $t->setPuesto ($fila[3]);

                $vueloOB = new Vuelo($fila[4]);
                $vueloOB->obtenerVueloId();
                $t->setVuelo($vueloOB);

                $t->setCheckIn($fila[5]);

                $tickets[] = $t;
            }
            $conexion->cerrar();
            return $tickets;
        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function obtenerDestinosFrecuentes()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO(null, null, null, null, $this->pasajero);

        try {
            $sql = $ticketDAO->destinosFrecuentes();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            $destinos = [];

            while ($fila = $conexion->registro()) {
                $destinos[] = [
                    "ciudad" => $fila[0],
                    "cantidad" => $fila[1]
                ];
            }

            $conexion->cerrar();
            return $destinos;

        } catch (Exception $e) {
            $conexion->cerrar();
            return $e;
        }
    }

    public function obtenerVuelosPorMes()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $ticketDAO = new TicketDAO(null, null, null, null, $this->pasajero);

        try {
            $sql = $ticketDAO->obtenerVuelosPorMes();
            $conexion->ejecutar($sql["sql"], $sql["parametros"]);

            $meses = [];

            while ($fila = $conexion->registro()) {
                $meses[] = [
                    "mes" => $fila[0],
                    "total" => $fila[1]
                ];
            }

            return $meses;
        } catch (Exception $e) {
            return [];
        }
    }




}