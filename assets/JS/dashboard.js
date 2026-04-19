$(document).ready(function () {

    // PACIENTES
    $("#btnPacientes").click(function () {

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/pacientes/pacientes.php", function () {

                $("#vista-dinamica").fadeIn(200);

            });

        });

    });


    // CITAS
    $("#btnCitas").click(function () {

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/citas/citas.php", function () {

                $("#vista-dinamica").fadeIn(200);

            });

        });

    });

});