<div id="vuelos">
  
</div>

<script>
    let pagina = 1;
    let cargando = false;
    let fin = false;

    function cargarVuelos() {
        if (cargando || fin) return;

        cargando = true;

        $.ajax({
            url: "ajax/cargarVuelos.php",
            type: "POST",
            data: { pag: pagina },
            success: function(html) {

                if ($.trim(html) === "") {
                    fin = true;
                    return;
                }

                $("#vuelos").append(html);

                pagina++;
                cargando = false;
            }
        });
    }

    cargarVuelos();

    // Detectar el scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
            cargarVuelos();
        }
    });

</script>
