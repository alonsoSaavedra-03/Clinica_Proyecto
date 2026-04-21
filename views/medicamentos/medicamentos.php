
    <div class="container-fluid mt-4">
        <div class="d-flex align-items-center gap-3 mb-4 bg-light p-3 rounded shadow-sm">
            <i class="fa-solid fa-pills fa-2x"></i>
            
        <h4 class="titulo">Gestión de Medicamentos</h4>
        </div>
    <!-- DASHBOARD -->
    <div class="row g-4">

        <!-- INVENTARIO -->
        <div class="col-md-4">
            <div class="card shadow border-0 card-inventario">
                <div class="card-body">
                    <h5><i class="fa-solid fa-pills"></i> Inventario</h5>
                    <h2 id="totalMedicamentos">0</h2>
                    <span class="badge bg-danger" id="alertasStock">0 críticos</span>
                </div>
            </div>
        </div>

        <!-- MOVIMIENTOS -->
        <div class="col-md-4">
            <div class="card shadow border-0 card-movimientos">
                <div class="card-body">
                    <h5><i class="fa-solid fa-arrows-rotate"></i> Movimientos</h5>
                    <button class="btn btn-primary btn-sm" id="btnKardex">
                        Ver Kardex
                    </button>
                    <p id="ultimaFecha"></p>
                </div>
            </div>
        </div>

        <!-- CATEGORIAS -->
        <div class="col-md-4">
            <div class="card shadow border-0 card-categoria">
                <div class="card-body">
                    <h5><i class="fa-solid fa-layer-group"></i> Categorías</h5>
                    <h2 id="totalCategorias">0</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- BUSCADOR -->
    <div class="mt-4">
        <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre o sustancia activa...">
    </div>

    <!-- Boton para abrir el modal de nuevo medicamento -->
    <div class="mt-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalMedicamento">
            <i class="fa-solid fa-plus"></i> Nuevo Medicamento
        </button>
    </div>

    <!-- Modal para agregar nuevo medicamento -->
        <div class="modal fade" id="modalMedicamento">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5>Nuevo Medicamento</h5>
        </div>
        <div class="modal-body">
            <input type="text" id="nombre" class="form-control mb-2" placeholder="Nombre">
            <input type="text" id="descripcion" class="form-control mb-2" placeholder="Descripción">
            <input type="number" id="stock" class="form-control mb-2" placeholder="Stock Inicial">
            <input type="number" id="precio" class="form-control mb-2" placeholder="Precio">
            <input type="date" id="fecha_vencimiento" class="form-control mb-2" placeholder="Fecha de Vencimiento">
            <input type="number" id="minimo" class="form-control mb-2" placeholder="Stock Mínimo">
            <input type="number" id="critico" class="form-control mb-2" placeholder="Stock Crítico">
            <input type="text" id="laboratorio" class="form-control mb-2" placeholder="Laboratorio">
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" onclick="guardarMedicamento()">Guardar</button>
        </div>
        </div>
    </div>
    </div>

    <!-- MODAL PARA MOVIMIENTOS -->
        <div class="modal fade" id="modalMovimiento">
    <div class="modal-dialog">
        <div class="modal-content">

        <div class="modal-header">
            <h5 id="tituloMovimiento"></h5>
        </div>

        <div class="modal-body">

            <input type="hidden" id="idMedicamento">

            <input type="number" id="cantidad" class="form-control mb-2" placeholder="Cantidad">

            <textarea id="motivo" class="form-control" placeholder="Motivo"></textarea>

        </div>

        <div class="modal-footer">
            <button class="btn btn-primary" onclick="guardarMovimiento()">Guardar</button>
        </div>

        </div>
    </div>
    </div>

<!-- MODAL KARDEX -->
    <div class="modal fade" id="modalKardex">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header bg-dark text-white">
            <h5><i class="fa-solid fa-clock-rotate-left"></i> Kardex de Movimientos</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- FILTROS -->
        <div class="p-3 border-bottom d-flex gap-2 flex-wrap">
            <input type="text" id="buscarKardex" class="form-control" placeholder="Buscar medicamento...">

            <select id="filtroTipo" class="form-select" style="max-width: 180px;">
            <option value="">Todos</option>
            <option value="ENTRADA">Entrada</option>
            <option value="SALIDA">Salida</option>
            </select>
        </div>

        <!-- TABLA -->
        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Medicamento</th>
                <th>Cantidad</th>
                <th>Saldo</th>
                <th>Motivo</th>
                </tr>
            </thead>
            <tbody id="tablaKardex"></tbody>
            </table>
        </div>

        </div>
    </div>
    </div>

    
    <!-- TABLA -->
    <div class="table-responsive mt-3">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Medicamento</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Fecha Vencimiento</th>
                    <th>Estado</th>
                    <th>Laboratorio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaMedicamentos"></tbody>
        </table>
    </div>

</div>
