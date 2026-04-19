$(document).ready(function () {

    cargarPacientes();

});

function cargarPacientes() {

    $.ajax({

        url: "../../PHP/api/pacientes.php?accion=listar",
        type: "GET",

        success: function (response) {

            let datos = JSON.parse(response);

            let html = "";

            datos.forEach(function (p) {

                html += `

                <tr>

                    <td>${p.ID_PACIENTE}</td>
                    <td>${p.DNI_PACIENTE}</td>
                    <td>${p.NOMBRES_PACIENTE}</td>
                    <td>${p.APELLIDOS_PACIENTE}</td>
                    <td>${p.CELULAR_PACIENTE}</td>

                    <td>
                        <button class="btn btn-warning btn-sm btnEditar"
                        data-id="${p.ID_PACIENTE}"
                        data-dni="${p.DNI_PACIENTE}"
                        data-nombre="${p.NOMBRES_PACIENTE}"
                        data-apellido="${p.APELLIDOS_PACIENTE}"
                        data-celular="${p.CELULAR_PACIENTE}"
                        data-fecha="${p.FECHA_NACIMIENTO}"
                        data-genero="${p.GENERO_PACIENTE}"
                        data-direccion="${p.DIRECCION_PACIENTE}"
                        data-correo="${p.CORREO_PACIENTE}"
                        data-username="${p.USERNAME}">
                        Editar
                        </button>

                        <button class="btn btn-danger btn-sm btnEliminar"
                        data-id="${p.ID_PACIENTE}">
                        Eliminar
                        </button>

                    </td>

                </tr>

                `;

            });

            $("#tablaPacientes").html(html);

        }

    });







    $(document).on("click", "#btnNuevoPaciente", function () {

        $("#modalPaciente").modal("show");

    });


    $(document).on("click", "#guardarPaciente", function () {

        let datos = {

            dni: $("#dni").val(),
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            celular: $("#celular").val(),
            fecha: $("#fecha").val(),
            genero: $("#genero").val(),
            direccion: $("#direccion").val(),
            correo: $("#correo").val(),
            username: $("#username").val(),
            password: $("#password").val()

        };

        $.ajax({

            url: "../../PHP/api/pacientes.php?accion=registrar",
            type: "POST",
            data: datos,

            success: function () {

                $("#modalPaciente").modal("hide");

                cargarPacientes();

            }

        });

    });


    $(document).on("click", ".btnEditar", function () {

        $("#idEditar").val($(this).data("id"));
        $("#dniEditar").val($(this).data("dni"));
        $("#nombreEditar").val($(this).data("nombre"));
        $("#apellidoEditar").val($(this).data("apellido"));
        $("#celularEditar").val($(this).data("celular"));
        $("#fechaEditar").val($(this).data("fecha"));
        $("#generoEditar").val($(this).data("genero"));
        $("#direccionEditar").val($(this).data("direccion"));
        $("#correoEditar").val($(this).data("correo"));
        $("#usernameEditar").val($(this).data("username"));
        $("#modalEditar").modal("show");

    });


    $(document).on("click", "#actualizarPaciente", function () {

        let datos = {

            id: $("#idEditar").val(),
            dni: $("#dniEditar").val(),
            nombre: $("#nombreEditar").val(),
            apellido: $("#apellidoEditar").val(),
            celular: $("#celularEditar").val(),
            fecha: $("#fechaEditar").val(),
            genero: $("#generoEditar").val(),
            direccion: $("#direccionEditar").val(),
            correo: $("#correoEditar").val(),
            username: $("#usernameEditar").val()

        };

        $.ajax({

            url: "../../PHP/api/pacientes.php?accion=editar",
            type: "POST",
            data: datos,

            success: function () {

                $("#modalEditar").modal("hide");

                cargarPacientes();

            }

        });

    });






    $(document).on("click", ".btnEliminar", function () {

        let id = $(this).data("id");

        Swal.fire({

            title: "¿Eliminar paciente?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Eliminar"

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({

                    url: "../../PHP/api/pacientes.php?accion=eliminar",
                    type: "POST",
                    data: { id: id },

                    success: function () {

                        Swal.fire("Eliminado", "", "success");

                        cargarPacientes();

                    }

                });

            }

        });

    });
}