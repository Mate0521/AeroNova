<?php
include ("../../comoponet/AutomatizacionEstados.php");
if(!isset($_SESSION['id'])){
    if ($_SESSION['rol'] != 'pasajero') {
        header("Location: ?pid=". base64_encode("Error"));
    }
    header("Location: ?pid=". base64_encode("Login"));
}




?>
<div class="card-body">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <input type="text" id="filtro" class="form-control" >
        </div>
    </div>
</div>
<?php 
include ("../Home.php");
?>
<script>

$( "#filtro" ).on( "keyup", function() {
    if($("#filtro").val().length >= 3){
        $.ajax({
            url: 'ajax/BuscarVueloAjax.php',
            type: 'POST',
            data: { filtro: $("#filtro").val() },
            success: function(response) {
                $("#vuelos").html(response);
            }
        });
    }
});

$(document).on("click", ".vuelo", function () {
    
    let id = $(this).data("id");

    $.ajax({
        url: "ajax/planesVueloAjax.php",
        type: "POST",
        data: {
            idVuelo: id
        },
        beforeSend: function () {
            $("#planesVuelo").html(
                "<div class='spinner-grow text-info' role='status'><span class='visually-hidden'>Loading...</span></div>"
            );
        },
        success: function (response) {
            $("#planesVuelo").html(response);
        },
        error: function () {
            $("#planesVuelo").html("<div class='text-danger'>Error cargando el vuelo.</div>");
        }
    });
});


</script>
