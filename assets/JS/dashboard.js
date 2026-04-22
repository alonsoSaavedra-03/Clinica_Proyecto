
    $(document).ready(function () {

    // INICIO
    $("#btnInicio").click(function () {

        let nombre = $(this).data("id");

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/inicio/inicio.php", function () {

                $("#nombrePersonal").text(nombre);
                console.log("Existe cargarDashboard:", typeof cargarDashboard);
                cargarDashboard();

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

            $("#vista-dinamica").load("../../views/admin/citas.php", function () {

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

        $(".modal.show").each(function () {
            let modal = bootstrap.Modal.getInstance(this);
            if (modal) modal.hide();
        });

        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();

        $("#vista-dinamica").fadeOut(200, function () {

            $("#vista-dinamica").load("../../views/documentos/documentos.php", function () {

                $("#vista-dinamica").fadeIn(200);

                initDocumentos();

            });

        });

    });

    // PAGO CITAS (CORREGIDO)
    $("#btnPagoCita").click(function () {
        $("#vista-dinamica").fadeOut(200, function () {
            // Se actualizó la ruta a admin/pago_cita.php
            $("#vista-dinamica").load("../../views/admin/pago_cita.php", function () {
                $("#vista-dinamica").fadeIn(200);
            });
        });
    });

    // CONFIGURACIÓN
        $("#btnconfig").click(function () {
        $("#vista-dinamica").fadeOut(200, function () {
            $("#vista-dinamica").load("../../views/admin/configuracion.php", function () {
                $("#vista-dinamica").fadeIn(200);
            });
        });
    });

    $("#btnInicio").click();


    function cargarDashboard() {
    fetch("../../PHP/api/dashboard.php")
        .then(res => {
            if (!res.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return res.json();
        })
        .then(data => {

            console.log("DATA DASHBOARD:", data);

            // VALIDACIÓN IMPORTANTE
            if (!data || !data.pacientes_mes || !data.citas_estado) {
                console.error("Datos incompletos:", data);
                return;
            }

            // 🔹 CARDS
            document.getElementById("totalPacientes").innerText = data.pacientes ?? 0;
            document.getElementById("citasHoy").innerText = data.citas_hoy ?? 0;
            document.getElementById("totalEmpleados").innerText = data.empleados ?? 0;
            document.getElementById("totalCitas").innerText = data.total_citas ?? 0;

            // DESTRUIR GRÁFICOS SI EXISTEN
            if (window.graficoLinea instanceof Chart) {
                window.graficoLinea.destroy();
            }

            if (window.graficoPastel instanceof Chart) {
                window.graficoPastel.destroy();
            }

            // GRAFICO LINEA
            const ctxLinea = document.getElementById("graficoLinea").getContext("2d");

            window.graficoLinea = new Chart(ctxLinea, {
                type: 'line',
                data: {
                    labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                    datasets: [{
                        label: 'Pacientes',
                        data: data.pacientes_mes,
                        fill: true
                    }]
                }
            });

            // GRAFICO PASTEL
            const ctxPastel = document.getElementById("graficoPastel").getContext("2d");

            window.graficoPastel = new Chart(ctxPastel, {
                type: 'pie',
                data: {
                    labels: data.citas_estado.labels,
                    datasets: [{
                        data: data.citas_estado.data
                    }]
                }
            });

        })
        .catch(error => {
            console.error("ERROR EN DASHBOARD:", error);
        });
}
});
    