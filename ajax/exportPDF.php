<?php

require "../fpdf/fpdf.php";

if (!isset($_POST["img"])) {
    die("No hay imagen");
}
$hoy= new DateTime();

$img = $_POST["img"];
$nombre = $_POST["name"] ?? "grafico";

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(0, 10, "Reporte: $nombre", 0, 1, "C");
$pdf->SetFont("Arial", "I", 16);
$pdf->Cell(0, 10, $hoy->format('Y-M-d H:i:s'), 0, 1, "L");
$pdf->Ln(5);

// Guardamos la imagen temporalmente
$imgData = str_replace("data:image/png;base64,", "", $img);
$imgData = base64_decode($imgData);

$tmpPath = "temp_" . uniqid() . ".png";
file_put_contents($tmpPath, $imgData);

// Insertamos en PDF
$pdf->Image($tmpPath, 10, 40, 180);

// Eliminamos imagen temporal
unlink($tmpPath);

$pdf->Output("D", $nombre.".pdf");
