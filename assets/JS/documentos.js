// 🔹 INICIALIZAR DOCUMENTOS
function initDocumentos(){

    cargarPacientesDocumentos();

    $("#buscadorPacienteDocumentos").off("keyup").on("keyup", function(){
        let val = $(this).val().toLowerCase();

        $("#tablaPacientesDocumentos tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });

}

// 🔹 CARGAR PACIENTES (RENOMBRADO)
function cargarPacientesDocumentos(){
    $.get("/Clinica_Proyecto/PHP/api/pacientes.php?accion=listar", function(data){

        let html = "";
        data = JSON.parse(data);

        data.forEach(p => {
            html += `
            <tr>
                <td>${p.DNI_PACIENTE}</td>
                <td>${p.NOMBRES_PACIENTE} ${p.APELLIDOS_PACIENTE}</td>
                <td>${p.CELULAR_PACIENTE}</td>
                <td>
                    <button class="btn btn-primary btn-sm btn-expediente"
                        data-id="${p.ID_PACIENTE}"
                        data-nombre="${p.NOMBRES_PACIENTE} ${p.APELLIDOS_PACIENTE}">
                        👁
                    </button>
                </td>
            </tr>`;
        });

        $("#tablaPacientesDocumentos").html(html);
    });
}

// 🔹 EVENTO DINÁMICO
$(document).on("click", ".btn-expediente", function(){

    let id = $(this).data("id");
    let nombre = $(this).data("nombre");

    $("#tituloPaciente").text("Expediente de: " + nombre);
    $("#docPacienteId").val(id);

    cargarExpedienteDocumentos(id);

    let modal = new bootstrap.Modal(document.getElementById('modalExpediente'));
    modal.show();
});

// 🔹 CARGAR EXPEDIENTE (RENOMBRADO)
function cargarExpedienteDocumentos(id){

    $.get(`/Clinica_Proyecto/PHP/api_expediente.php?id=${id}`, function(data){

        data = JSON.parse(data);

        let recetasHTML = "";
        if(data.recetas.length === 0){
            recetasHTML = "<tr><td colspan='4'>Sin recetas</td></tr>";
        }else{
            data.recetas.forEach(r => {
                recetasHTML += `
                <tr>
                    <td>${r.fecha}</td>
                    <td>${r.medicamento}</td>
                    <td>${r.dosis}</td>
                    <td>${r.indicaciones}</td>
                </tr>`;
            });
        }
        $("#tablaRecetas").html(recetasHTML);

        let docsHTML = "";
        if(data.documentos.length === 0){
            docsHTML = "<li class='list-group-item'>Sin documentos</li>";
        }else{
            data.documentos.forEach(d => {
                docsHTML += `
                <li class="list-group-item d-flex justify-content-between">
                    <span>${d.tipo} - ${d.descripcion}</span>
                    <a href="/Clinica_Proyecto/uploads/${d.archivo.split('/').pop()}" 
                        target="_blank" 
                        class="btn btn-sm btn-primary">
                        Ver
                    </a>
                </li>`;
            });
        }
        $("#listaDocumentos").html(docsHTML);

    });
}

// 🔹 SUBIR DOCUMENTO (MEJORADO PARA SPA)
$(document).off("submit", "#formDocumento").on("submit", "#formDocumento", function(e){
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "/Clinica_Proyecto/PHP/api_documentos.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){

            res = JSON.parse(res);

            if(res.error){
                Swal.fire("Error", res.error, "error");
            }else{
                Swal.fire("Correcto", "Documento subido", "success");
                cargarExpedienteDocumentos($("#docPacienteId").val());
            }
        }
    });
});