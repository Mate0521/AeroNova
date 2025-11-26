<?php
if (!isset($_SESSION["id"])) {
    header("Location: ?pid=" . base64_encode("Login"));
    exit();
}

if ($_SESSION["rol"] !== "pasajero") {
    header("Location: ?pid=" . base64_encode("Error"));
    exit();
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
<div id="vuelos">

</div>


<script>

    let pagina = 1;
    let cargando = false;
    let fin = false;
    let modoFiltro = false;  

    function resetScroll() {
        pagina = 1;
        cargando = false;
        fin = false;
        $("#vuelos").html("");
    }

    function cargarVuelos() {
        if (cargando || fin) return;

        let filtro = $("#filtro").val().trim();
        modoFiltro = filtro.length >= 3;

        let address = modoFiltro? "ajax/BuscarVueloAjax.php": "ajax/cargarVuelos.php";

        cargando = true;

        $.ajax({
            url: address,
            type: "POST",
            data: {
                filtro: filtro,
                pag: pagina
            },
            success: function(html) {

                html = $.trim(html);

                if (html === "") {
                    fin = true;
                    return;
                }

                $("#vuelos").append(html);

                pagina++;
                cargando = false;
            }
        });
    }

    $("#filtro").on("keyup", function() {
        let texto = $(this).val().trim();

        if (texto.length >= 3) {
            resetScroll();
            cargarVuelos();
        } else {
            cargarVuelos();

        }
    });

    $(window).on("scroll", function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
            cargarVuelos();
        }
    });

    cargarVuelos();

</script>

