$(document).ready(function () {
    cargarPagos();
});

/* ========================
   CARGAR SELECT DE CITAS
======================== */
function cargarCitasSelect() {
    $.ajax({
        url: "../../PHP/api/citas.php?accion=listar",
        type: "GET",
        success: function (res) {
            // Verificamos si res ya es objeto o es string
            let citas = typeof res === 'string' ? JSON.parse(res) : res;
            
            $("#id_cita").html('<option value="">Seleccionar cita asociada</option>');
            citas.forEach(c => {
                // Usamos nombres de columnas según tu base de datos
                let id = c.ID_CITA || c.id;
                let paciente = (c.NOMBRES_PACIENTE || '') + " " + (c.APELLIDOS_PACIENTE || '');
                let fecha = c.FECHA_CITA || c.fecha;

                $("#id_cita").append(`
                    <option value="${id}">
                        Cita #${id} - ${paciente} (${fecha})
                    </option>
                `);
            });
        }
    });
}

/* ========================
   LISTAR PAGOS EN TABLA
======================== */
function cargarPagos() {
    $.ajax({
        url: "../../PHP/api/pago_cita.php?accion=listar",
        type: "GET",
        success: function (res) {
            let pagos = typeof res === 'string' ? JSON.parse(res) : res;
            let html = "";

            if (pagos.length === 0) {
                html = '<tr><td colspan="9" class="text-center">No hay registros de pagos</td></tr>';
            } else {
                pagos.forEach(p => {
                    // Normalización de datos (Soporta mayúsculas y minúsculas)
                    let id = p.ID_PAGO || p.id;
                    let id_cita = p.ID_CITA || p.id_cita;
                    let monto = p.MONTO_TOTAL || p.monto;
                    let metodo = p.METODO_PAGO || p.metodo;
                    let estado = p.ESTADO_PAGO || p.estado;
                    let operacion = p.NUMERO_OPERACION || p.operacion || '---';
                    let fecha_p = p.FECHA_PAGO || p.fecha || '---';
                    let paciente = (p.NOMBRES_PACIENTE || p.paciente || 'S/N') + " " + (p.APELLIDOS_PACIENTE || '');

                    html += `
                    <tr>
                        <td>${id}</td>
                        <td>Cita #${id_cita}</td>
                        <td>${paciente}</td>
                        <td><strong>S/ ${parseFloat(monto).toFixed(2)}</strong></td>
                        <td>${metodo}</td>
                        <td>
                            <span class="badge ${estado === 'COMPLETADO' ? 'bg-success' : 'bg-warning text-dark'}">
                                ${estado}
                            </span>
                        </td>
                        <td><small>${operacion}</small></td>
                        <td>${fecha_p}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btnEditarPago" 
                                data-id="${id}"
                                data-cita="${id_cita}"
                                data-monto="${monto}"
                                data-metodo="${metodo}"
                                data-estado="${estado}"
                                data-operacion="${operacion}">
                                Editar
                            </button>
                            <button class="btn btn-danger btn-sm btnEliminarPago" data-id="${id}">
                                Eliminar
                            </button>
                        </td>
                    </tr>`;
                });
            }
            $("#tablaPagos").html(html);
        },
        error: function() {
            $("#tablaPagos").html('<tr><td colspan="9" class="text-center text-danger">Error al conectar con la API</td></tr>');
        }
    });
}

/* ========================
   ABRIR MODAL NUEVO PAGO
======================== */
$("#btnNuevoPago").click(function () {
    $("#tituloModalPago").text("Registrar Nuevo Pago");
    $("#guardarPago").show();
    $("#actualizarPago").hide();
    
    // Limpiar campos manualmente para evitar errores de form
    $("#idPago").val("");
    $("#id_cita").val("");
    $("#monto").val("");
    $("#operacion").val("");
    $("#metodo").val("EFECTIVO");
    $("#estado_pago").val("PENDIENTE");
    
    cargarCitasSelect();
    $("#modalPago").modal("show");
});

/* ========================
   REGISTRAR PAGO
======================== */
$("#guardarPago").click(function () {
    let datos = {
        id_cita: $("#id_cita").val(),
        monto: $("#monto").val(),
        metodo: $("#metodo").val(),
        estado: $("#estado_pago").val(),
        operacion: $("#operacion").val()
    };

    if(!datos.id_cita || !datos.monto) {
        Swal.fire("Atención", "Cita y Monto son obligatorios", "info");
        return;
    }

    $.ajax({
        url: "../../PHP/api/pago_cita.php?accion=registrar",
        type: "POST",
        data: datos,
        success: function (res) {
            $("#modalPago").modal("hide");
            Swal.fire("¡Éxito!", "El pago se guardó correctamente", "success");
            cargarPagos();
        }
    });
});

/* ========================
   ABRIR MODAL EDITAR
======================== */
$(document).on("click", ".btnEditarPago", function () {
    let id = $(this).data("id");
    let cita = $(this).data("cita");

    $("#tituloModalPago").text("Editar Pago #" + id);
    $("#guardarPago").hide();
    $("#actualizarPago").show();

    cargarCitasSelect();

    // Llenar campos con la data del botón
    $("#idPago").val(id);
    $("#monto").val($(this).data("monto"));
    $("#metodo").val($(this).data("metodo"));
    $("#estado_pago").val($(this).data("estado"));
    $("#operacion").val($(this).data("operacion"));

    // Esperar un poco a que el select se llene antes de asignar el valor
    setTimeout(function () {
        $("#id_cita").val(cita);
    }, 400);

    $("#modalPago").modal("show");
});

/* ========================
   ACTUALIZAR PAGO
======================== */
$(document).on("click", "#actualizarPago", function () {
    let datos = {
        id: $("#idPago").val(),
        id_cita: $("#id_cita").val(),
        monto: $("#monto").val(),
        metodo: $("#metodo").val(),
        estado: $("#estado_pago").val(),
        operacion: $("#operacion").val()
    };

    $.ajax({
        url: "../../PHP/api/pago_cita.php?accion=editar",
        type: "POST",
        data: datos,
        success: function () {
            $("#modalPago").modal("hide");
            Swal.fire("Actualizado", "Pago actualizado correctamente", "success");
            cargarPagos();
        }
    });
});

/* ========================
   ELIMINAR PAGO
======================== */
$(document).on("click", ".btnEliminarPago", function () {
    let id = $(this).data("id");

    Swal.fire({
        title: "¿Eliminar registro?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../../PHP/api/pago_cita.php?accion=eliminar",
                type: "POST",
                data: { id: id },
                success: function () {
                    Swal.fire("Eliminado", "El registro ha sido borrado.", "success");
                    cargarPagos();
                }
            });
        }
    });
});