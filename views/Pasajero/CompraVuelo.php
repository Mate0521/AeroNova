<?php
if(!isset($_SESSION['id'])){
    header("Location: ?pid=". base64_encode("Login"));
}

if($_POST['reservarVuelo']){
    $idVuelo = base64_decode($_POST['idV']);

    $vuelo=new Vuelo($idVuelo);
    $vuelo->obtenerVueloId();
}
?>


