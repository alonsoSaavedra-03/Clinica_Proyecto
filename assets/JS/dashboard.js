$(document).ready(function () {

    // INICIO
    $("#btnInicio").click(function () {

        let nombre = $(this).data("id");

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/inicio/inicio.php", function () {

                $("#nombrePersonal").text(nombre);

                $("#vista-dinamica").fadeIn(200);

            });

        });

    });

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


    $("#btnMedicamentos").click(function () {

    $("#vista-dinamica").fadeOut(200, function () {

        $("#vista-dinamica").load("../../views/medicamentos/medicamentos.php", function () {

            $("#vista-dinamica").fadeIn(200);

            initMedicamentos();

        });

    });

});

 // DOCUMENTOS
    $("#btnDocumentos").click(function () {

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/documentos/documentos.php", function () {

                $("#vista-dinamica").fadeIn(200);

            });

        });

    });

    $("#btnInicio").click();
});