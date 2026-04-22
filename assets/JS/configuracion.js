$(document).ready(function () {
    cargarDatosPerfil();
});

/* ========================
   CARGAR DATOS DEL PERFIL
======================== */
function cargarDatosPerfil() {
    $.ajax({
        url: "../../PHP/api_configuracion.php?accion=listar",
        type: "GET",
        success: function (res) {
            // DEPURACIÓN: Abre la consola (F12) y mira qué nombres aparecen aquí
            console.log("Respuesta del servidor:", res);

            let u = typeof res === 'string' ? JSON.parse(res) : res;

            if (u.error) {
                console.error("Error desde PHP:", u.error);
                return;
            }

            // Rellenar la tabla informativa (Vista Principal)
            // IMPORTANTE: Los nombres (u.NOMBRES, u.CORREO) deben ser iguales a los del SELECT en PHP
            $("#conf-nombre-completo").text((u.NOMBRES || '') + " " + (u.APELLIDOS || ''));
            $("#conf-username").text(u.USERNAME || '---');
            $("#conf-correo").text(u.CORREO || '---');
            $("#conf-celular").text(u.CELULAR || '---');
            $("#conf-especialidad").text(u.DETALLE || 'Personal');

            // Guardar datos en el botón de editar para el modal
            $("#btnEditarPerfil").data("perfil", u);
        },
        error: function(xhr) {
            console.error("Error AJAX:", xhr.responseText);
            Swal.fire("Error", "No se pudo cargar la información del perfil", "error");
        }
    });
}

/* ========================
   ABRIR MODAL CONFIGURACIÓN
======================== */
$("#btnEditarPerfil").click(function () {
    let u = $(this).data("perfil");

    if(!u) return; // Evita errores si no cargó la data

    $("#tituloModalConfig").text("Actualizar Mis Datos");
    
    // Llenar campos del modal con la data guardada
    $("#idUsuarioConf").val(u.ID);
    $("#correoConf").val(u.CORREO);
    $("#celularConf").val(u.CELULAR);
    
    // Limpiar campos de contraseña por seguridad
    $("#passNueva").val("");
    $("#passConfirmar").val("");

    $("#modalConfig").modal("show");
});

/* ========================
   ACTUALIZAR PERFIL
======================== */
$("#btnActualizarPerfil").click(function () {
    let pass = $("#passNueva").val();
    let confirm = $("#passConfirmar").val();

    if (pass !== "" && pass !== confirm) {
        Swal.fire("Atención", "Las contraseñas no coinciden", "warning");
        return;
    }

    let datos = {
        correo: $("#correoConf").val(),
        celular: $("#celularConf").val(),
        password: pass 
    };

    if(!datos.correo || !datos.celular) {
        Swal.fire("Atención", "El correo y celular son obligatorios", "info");
        return;
    }

    $.ajax({
        url: "../../PHP/api_configuracion.php?accion=editar",
        type: "POST",
        data: datos,
        success: function (res) {
            let response = typeof res === 'string' ? JSON.parse(res) : res;
            
            if(response.success) {
                $("#modalConfig").modal("hide");
                Swal.fire("¡Éxito!", "Tus datos se actualizaron correctamente", "success");
                cargarDatosPerfil(); 
            } else {
                Swal.fire("Error", response.error || "No se pudo actualizar", "error");
            }
        },
        error: function() {
            Swal.fire("Error", "Error de servidor al actualizar", "error");
        }
    });
});

/* ========================
   ELIMINAR PERFIL
======================== */
function eliminarUsuario(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará al usuario del sistema.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../../PHP/api_configuracion.php?accion=eliminar",
                type: "POST",
                data: { id: id },
                success: function (res) {
                    Swal.fire("Eliminado", "Usuario borrado exitosamente.", "success");
                },
                error: function(err) {
                    try {
                        let msg = JSON.parse(err.responseText);
                        Swal.fire("Error", msg.error, "error");
                    } catch(e) {
                        Swal.fire("Error", "No se pudo completar la acción", "error");
                    }
                }
            });
        }
    });
}