<?php

if (!isset($_SESSION["id"])) {
    header("Location: ?pid=" . base64_encode("Login"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ?pid=" . base64_encode("panelPasajero"));
    exit();
}

$idVuelo = intval($_POST["idVuelo"]);
$clase   = trim($_POST["clase"]);
$precioCliente = floatval($_POST["precio"]);
$puesto  = trim($_POST["puesto"]);


if ($idVuelo <= 0 || !in_array($clase, ["eco", "clas", "bus"])) {
    echo "<div class='alert alert-danger'>Datos inválidos</div>";
    exit();
}


$vuelo = new Vuelo($idVuelo);
$vuelo->obtenerVueloId();

$pilotoP = $vuelo->getPilotoPrincipal();
$coPiloto= $vuelo->getCopiloto();


$precioBase = floatval($_SESSION["v{$idVuelo}"]["precio"]);
$multiplicador = [
    "eco"  => 1.00,
    "clas" => 1.15,
    "bus"  => 1.40
];

$precioReal = $precioBase * $multiplicador[$clase] ;


// Validación anti-hack
if (round($precioReal, 4 )!= round($precioCliente, 4)) {
    echo "<h3 style='color:red'>Error: El precio no coincide. Intente nuevamente.</h3>";
    exit();
}

switch ($clase){
    case "eco" : 
        $clase="economica";
        break;
    case "clas":
        $clase="clasica";
        break;
    case "bus":
        $clase="buisnes";
        break;
}


$ticket = new Ticket(
    "",             
    1,              
    $precioReal,    
    $puesto,        
    $_SESSION["id"],
    $idVuelo,       
    0,                      
);

$ticket->crearTicket();  
if (!$ticket->getIdTicket()) {
    echo "<div class='alert alert-danger'>Error al guardar ticket.</div>";
    exit();
}
$ticketId=$ticket->getIdTicket();

// carpeta
$dir = __DIR__ . "/../../tickets";
if (!is_dir($dir)) mkdir($dir, 0775, true);

//qr
$qrFile = "{$dir}/qr_{$ticketId}.png";
$qrData = "TICKET:{$ticketId}|USER:{$_SESSION['id']}|VUELO:{$idVuelo}";
QRcode::png($qrData, $qrFile, QR_ECLEVEL_M, 5);


class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0,70,140);
        $this->Cell(0,10,'AeroNova - Ticket de Vuelo',0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'AeroNova • Documento digital oficial',0,1,'C');
        $this->Cell(0,5,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdfFile = "{$dir}/ticket_{$ticketId}.pdf";

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

//head
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,6,"Ticket ID: ".$ticketId,0,1);
$pdf->Cell(0,6,"Fecha Emisión: ".date("Y-m-d H:i:s"),0,1);
$pdf->Ln(4);

//pasajero
$pasajero = new Pasajero($_SESSION["id"]);
$pasajero->obtenerPasajeroId();

$pdf->SetFont('Arial','B',13);
$pdf->SetFillColor(225,225,225);
$pdf->Cell(0,8,"Datos del Pasajero",0,1,'L',true);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,6,"Nombre: ".$pasajero->getNombre()." ".$pasajero->getApellido(),0,1);
$pdf->Cell(0,6,"Telefono: ".$pasajero->getTelefono(),0,1);
$pdf->Cell(0,6,"Correo: ".$pasajero->getCorreo(),0,1);
$pdf->Ln(4);

//datois vuelo
$pdf->SetFont('Arial','B',13);
$pdf->Cell(0,8,"Datos del Vuelo",0,1,'L',true);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,6,"Ruta: ".$vuelo->getRuta()->getOrigen()->getNombre()." → ".$vuelo->getRuta()->getDestino()->getNombre(),0,1);
$pdf->Cell(0,6,"Fecha: ".$vuelo->getFecha(),0,1);
$pdf->Cell(0,6,"Hora: ".$vuelo->getHoraDespegue(),0,1);
$pdf->Cell(0,6,"Avión: ".$vuelo->getAvion()->getModelo(),0,1);
$pdf->Cell(0,6,"Clase: ".strtoupper($clase),0,1);
$pdf->Cell(0,6,"Asiento: ".$puesto,0,1);
$pdf->Cell(0,6,"Precio: $".number_format($precioReal,0,",","."),0,1);
$pdf->Ln(4);

//pilotos

$pdf->SetFont('Arial','B',13);
$pdf->Cell(0,8,"Tripulación",0,1,'L',true);

$pdf->SetFont('Arial','',12);

$pdf->Cell(0,6,"• ". $pilotoP->getNombre()." ". $pilotoP->getApellido(),0,1);
$pdf->Cell(0,6,"• ".$coPiloto->getNombre()." ".$coPiloto->getApellido(),0,1);

$pdf->Ln(6);

//qr
$pdf->SetFont('Arial','B',13);
$pdf->Cell(0,8,"Código QR del Ticket",0,1,'L',true);

$pdf->Image($qrFile, $pdf->GetX()+40, $pdf->GetY(), 80, 80);
$pdf->Ln(90);

$pdf->Output("F", $pdfFile);

// ==== ENVIAR AL CORREO ====
$asunto = "AeroNova - Su Ticket #{$ticketId}";
$mensaje = "Hola {$pasajero->getNombre()} {$pasajero->getApellido()}\n\n";
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
$body .= "Content-Type: application/pdf; name=\"ticket_{$ticketId}.pdf\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"ticket_{$ticketId}.pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= $fileContent . "\r\n";
$body .= "--{$boundary}--";

mail($pasajero->getCorreo(), $asunto, $body, $headers);

?>

<div class="container text-center mt-5">
    <h2 class="text-success">Compra realizada con éxito</h2>
    <p>Tu ticket ha sido generado correctamente.</p>
    <a href="tickets/ticket_<?= $ticketId ?>" class="btn btn-primary" target="_blank">
        Descargar Ticket PDF
    </a>
    <a href="?pid=<?= base64_encode('panelPasajero') ?>" class="btn btn-secondary">Volver al Panel</a>
</div>
