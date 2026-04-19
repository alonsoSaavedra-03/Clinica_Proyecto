$(document).ready(function () {

    cargarCitas();

});

function cargarPacientes() {

    $.ajax({

        url: "../../PHP/api/pacientes.php?accion=listar",

        success: function (res) {

            let pacientes = JSON.parse(res);

            $("#paciente").html('<option>Seleccionar paciente</option>');

            pacientes.forEach(p => {

                $("#paciente").append(`
                <option value="${p.ID_PACIENTE}">
                ${p.NOMBRES_PACIENTE} ${p.APELLIDOS_PACIENTE}
                </option>
                `);

            });

        }

    });

}

function cargarEmpleados() {

    $.ajax({

        url: "../../PHP/api/empleados.php?accion=listar",

        success: function (res) {

            let empleados = JSON.parse(res);

            $("#empleado").html('<option>Seleccionar médico</option>');

            empleados.forEach(e => {

                $("#empleado").append(`
                <option value="${e.ID_EMPLEADO}">
                ${e.NOMBRES_EMPLEADO} ${e.APELLIDOS_EMPLEADO}
                </option>
                `);

            });

        }

    });

}

function cargarCitas() {

    $.ajax({

        url: "../../PHP/api/citas.php?accion=listar",
        type: "GET",

        success: function (res) {

            let citas = JSON.parse(res);

            let html = "";

            citas.forEach(c => {

                html += `
                <tr>

                <td>${c.ID_CITA}</td>

                <td>
                ${c.NOMBRES_PACIENTE}
                ${c.APELLIDOS_PACIENTE}
                </td>

                <td>
                ${c.NOMBRES_EMPLEADO}
                ${c.APELLIDOS_EMPLEADO}
                </td>

                <td>${c.FECHA_CITA}</td>

                <td>${c.MOTIVO}</td>

                <td>${c.ESTADO}</td>

                <td>

                <button 
                class="btn btn-warning btn-sm btnEditar"

                data-id="${c.ID_CITA}"
                data-paciente="${c.ID_PACIENTE}"
                data-empleado="${c.ID_EMPLEADO}"
                data-fecha="${c.FECHA_CITA}"
                data-motivo="${c.MOTIVO}"
                data-estado="${c.ESTADO}"
                data-observaciones="${c.OBSERVACIONES}"

                >
                Editar
                </button>

                <button 
                class="btn btn-danger btn-sm btnEliminar"
                data-id="${c.ID_CITA}"
                >
                Eliminar
                </button>

                </td>

                </tr>
                `;

            });

            $("#tablaCitas").html(html);

        }

    });

}

$("#btnNuevaCita").click(function () {

    $("#guardarCita").show();
    $("#actualizarCita").hide();

    cargarPacientes();
    cargarEmpleados();

    $("#modalCita").modal("show");

});

$("#guardarCita").click(function () {

    let datos = {

        paciente: $("#paciente").val(),
        empleado: $("#empleado").val(),
        fecha: $("#fecha").val(),
        motivo: $("#motivo").val(),
        estado: $("#estado").val(),
        observaciones: $("#observaciones").val()

    };

    $.ajax({

        url: "../../PHP/api/citas.php?accion=registrar",
        type: "POST",
        data: datos,

        success: function () {

            $("#modalCita").modal("hide");

            cargarCitas();

        }

    });

});

$(document).on("click", ".btnEditar", function () {

    let id = $(this).data("id");
    let paciente = $(this).data("paciente");
    let empleado = $(this).data("empleado");

    $("#guardarCita").hide();
    $("#actualizarCita").show();

    cargarPacientes();
    cargarEmpleados();

    $("#idCita").val(id);
    $("#fecha").val($(this).data("fecha"));
    $("#motivo").val($(this).data("motivo"));
    $("#estado").val($(this).data("estado"));
    $("#observaciones").val($(this).data("observaciones"));

    setTimeout(function () {

        $("#paciente").val(paciente);
        $("#empleado").val(empleado);

    }, 300);

    $("#modalCita").modal("show");

});




$(document).on("click", "#actualizarCita", function () {

    let datos = {

        id: $("#idCita").val(),
        paciente: $("#paciente").val(),
        empleado: $("#empleado").val(),
        fecha: $("#fecha").val(),
        motivo: $("#motivo").val(),
        estado: $("#estado").val(),
        observaciones: $("#observaciones").val()

    };
    console.log(datos);
    $.ajax({

        url: "../../PHP/api/citas.php?accion=editar",
        type: "POST",
        data: datos,

        success: function (res) {

            console.log("Respuesta:", res);

            $("#modalCita").modal("hide");

            cargarCitas();

        },

        error: function (e) {
            console.log("Error:", e);
        }

    });

});

$(document).on("click", ".btnEliminar", function () {

    let id = $(this).data("id");

    Swal.fire({
        title: "¿Eliminar cita?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: "../../PHP/api/citas.php?accion=eliminar",
                type: "POST",
                data: { id: id },

                success: function () {

                    Swal.fire(
                        "Eliminado",
                        "La cita fue eliminada correctamente",
                        "success"
                    );

                    cargarCitas();

                }

            });

        }

    });

});