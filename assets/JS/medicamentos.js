var tipoMovimiento = "";

$(document).ready(function(){
    cargarMedicamentos();
    cargarDashboard();
    cargarKardex();

    $("#buscador").on("keyup", function(){
        let valor = $(this).val().toLowerCase();

        $("#tablaMedicamentos tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
        });
    });

});

// Función para cargar los medicamentos desde la API
function cargarMedicamentos(){

    $.ajax({
        url: "/Clinica_Proyecto/PHP/api_medicamentos.php",
        method: "GET",
        dataType: "json",
        success: function(data){

            let html = "";

            let total = data.length;
            let criticos = 0;

            data.forEach(m => {

                let stock = parseInt(m.stock);
                let min = parseInt(m.stock_minimo);
                let crit = parseInt(m.stock_critico);

                let estado = "";

                if(stock <= crit){
                    estado = `<span class="badge badge-critico">Crítico</span>`;
                    criticos++;
                }else if(stock <= min){
                    estado = `<span class="badge badge-bajo">Bajo</span>`;
                }else{
                    estado = `<span class="badge badge-normal">Normal</span>`;
                }

                html += `
                    <tr>
                        <td>${m.nombre}</td>
                        <td>${m.descripcion || ""}</td>
                        <td>${stock}</td>
                        <td>${m.precio}</td>
                        <td>${m.fecha_vencimiento}</td>
                        <td>${estado}</td>
                        <td>${m.laboratorio}</td>
                        <td>
                            <button onclick="abrirMovimiento(${m.id}, 'ENTRADA')" class="btn btn-success btn-sm">+</button>
                            <button onclick="abrirMovimiento(${m.id}, 'SALIDA')" class="btn btn-danger btn-sm">-</button>
                        </td>
                    </tr>
                `;
            });

            $("#tablaMedicamentos").html(html);

            $("#totalMedicamentos").text(total);
            $("#alertasStock").text(criticos + " críticos");
        }
    });
}

// Función para cargar los datos del dashboard
function cargarDashboard(){

    $.ajax({
        url: "/Clinica_Proyecto/PHP/dashboard_medicamentos.php",
        method: "GET",
        dataType: "json",
        success: function(data){

            console.log("Dashboard:", data);

            $("#totalMedicamentos").text(data.totalMedicamentos);
            $("#alertasStock").text(data.criticos + " críticos");
            $("#totalCategorias").text(data.totalCategorias);

            if(data.ultimaFecha){
                $("#ultimaFecha").text("Último: " + data.ultimaFecha);
            }else{
                $("#ultimaFecha").text("Sin movimientos");
            }
        }
    });
}

// Función para abrir el modal de movimiento (entrada/salida)
function abrirMovimiento(id, tipo){

    tipoMovimiento = tipo;
    $("#idMedicamento").val(id);

    $("#tituloMovimiento").text(
        tipo === "ENTRADA" ? "Entrada de Stock" : "Salida de Stock"
    );

    $("#cantidad").val("");
    $("#motivo").val("");

    let modal = new bootstrap.Modal(document.getElementById('modalMovimiento'));
    modal.show();
}

// Función para guardar un nuevo movimiento (entrada o salida)
function guardarMovimiento(){

    let data = {
        id_medicamento: $("#idMedicamento").val(),
        tipo: tipoMovimiento,
        cantidad: $("#cantidad").val(),
        motivo: $("#motivo").val()
    };

    fetch("/Clinica_Proyecto/PHP/api_kardex.php", {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {

        if(res.error){
            Swal.fire("Error", res.error, "error");
        }else{
            Swal.fire("Correcto", "Movimiento registrado", "success");
            $("#modalMovimiento").modal("hide");
            cargarMedicamentos();
        }

    });
}


// Función para guardar un nuevo medicamento desde el modal
function guardarMedicamento(){

    let data = {
        nombre: $("#nombre").val(),
        descripcion: $("#descripcion").val(),
        stock: $("#stock").val(),
        precio: $("#precio").val(),
        fecha_vencimiento: $("#fecha_vencimiento").val(),
        stock_minimo: $("#minimo").val(),
        stock_critico: $("#critico").val(),
        laboratorio: $("#laboratorio").val()
    };

    fetch("/Clinica_Proyecto/PHP/api_medicamentos.php", {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {

        if(res.error){
            Swal.fire("Error", res.error, "error");
        }else{
            Swal.fire("Correcto", "Medicamento creado", "success");
            $("#modalMedicamento").modal("hide");
            cargarMedicamentos();
        }

    });
}

$(document).ready(function(){
    console.log("JS funcionando...");
    cargarMedicamentos();
});



$(document).on("click", "#btnKardex", function(){

    console.log("CLICK KARDEX"); // prueba

    cargarKardex();

    let modal = new bootstrap.Modal(document.getElementById('modalKardex'));
    modal.show();

});

// Función para cargar el kardex desde la API
function cargarKardex(){
    console.log("Cargando kardex...");
    $.ajax({
        url: "/Clinica_Proyecto/PHP/api_kardex_listado.php",
        method: "GET",
        dataType: "json",
        success: function(data){

            renderKardex(data);

            // 🔍 BUSCADOR
            $("#buscarKardex").on("keyup", function(){
                filtrarKardex(data);
            });

            $("#filtroTipo").on("change", function(){
                filtrarKardex(data);
            });

        }
    });
}

// Función para renderizar el kardex en la tabla
function renderKardex(data){

    let html = "";

    if(data.length === 0){
        html = `<tr><td colspan="6" class="text-center">Sin movimientos</td></tr>`;
    }

    data.forEach(m => {

        let badge = m.tipo === "ENTRADA"
            ? `<span class="badge bg-success">Entrada</span>`
            : `<span class="badge bg-danger">Salida</span>`;

        let cantidad = m.tipo === "ENTRADA"
            ? `+${m.cantidad}`
            : `-${m.cantidad}`;

        html += `
            <tr>
                <td>${m.fecha}</td>
                <td>${badge}</td>
                <td>${m.nombre}</td>
                <td>${cantidad}</td>
                <td>${m.saldo}</td>
                <td>${m.motivo}</td>
            </tr>
        `;
    });

    $("#tablaKardex").html(html);
}

// Función para filtrar el kardex por texto y tipo
function filtrarKardex(data){

    let texto = $("#buscarKardex").val().toLowerCase();
    let tipo = $("#filtroTipo").val();

    let filtrado = data.filter(m => {

        let coincideTexto = m.nombre.toLowerCase().includes(texto);
        let coincideTipo = tipo === "" || m.tipo === tipo;

        return coincideTexto && coincideTipo;
    });

    renderKardex(filtrado);
}



// Función para inicializar el módulo de medicamentos desde el dashboard
function initMedicamentos(){
    cargarMedicamentos();
    cargarDashboard();

    $("#buscador").on("keyup", function(){
        let valor = $(this).val().toLowerCase();

        $("#tablaMedicamentos tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1)
        });
    });
}