<?php
if(!isset($_SESSION['idUsuario'])){
    header("Location: ?pid=". base64_encode("Login"));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idVuelo = base64_decode($_POST['idV']);
    $ticket =new Ticket("", "", "", "",$_SESSION['id'], $idVuelo);
    $precio = $ticket->calcularPrecio();

}