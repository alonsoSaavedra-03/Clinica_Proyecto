<div class="card shadow-sm p-4">
    
    <div class="d-flex justify-content-between mb-3">
        <h4 class="titulo">Gestión de Citas</h4>

        <button class="btn btn-primary" id="btnNuevaCita">
            Nueva Cita
        </button>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tablaCitas">
        </tbody>
    </table>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalCita">
    
    <div class="modal-dialog">
        
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 id="tituloModalCita">Nueva Cita</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="idCita">

                <select id="paciente" class="form-control mb-2">
                    <option value="">Seleccionar paciente</option>
                </select>

                <select id="empleado" class="form-control mb-2">
                    <option value="">Seleccionar médico</option>
                </select>

                <input 
                    type="datetime-local"
                    id="fecha"
                    class="form-control mb-2"
                >

                <input 
                    type="text"
                    id="motivo"
                    class="form-control mb-2"
                    placeholder="Motivo"
                >

                <select id="estado" class="form-control mb-2">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ATENDIDO">Atendido</option>
                    <option value="CANCELADO">Cancelado</option>
                </select>

                <textarea
                    id="observaciones"
                    class="form-control"
                    placeholder="Observaciones"
                ></textarea>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="guardarCita">
                    Guardar
                </button>

                <button class="btn btn-success" id="actualizarCita">
                    Actualizar
                </button>
            </div>

        </div>
    </div>
</div>

<script src="../../assets/JS/citas.js"></script>