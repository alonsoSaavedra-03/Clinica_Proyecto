<div class="card shadow-sm p-4">
    
    <div class="d-flex justify-content-between mb-3">
        <h4 class="titulo">Gestión de Pagos</h4>

        <button class="btn btn-primary" id="btnNuevoPago">
            <i class="fas fa-plus"></i> Registrar Pago
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cita</th>
                    <th>Paciente</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Nro. Operación</th>
                    <th>Fecha Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody id="tablaPagos">
                </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 id="tituloModalPago">Detalle del Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idPago">

                <div class="mb-2">
                    <label class="form-label">Cita Asociada:</label>
                    <select id="id_cita" class="form-control">
                        <option value="">Seleccionar cita</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Fecha de Pago:</label>
                    <input type="date" id="fecha_pago" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label">Monto a Cobrar (S/):</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        id="monto" 
                        class="form-control" 
                        placeholder="0.00"
                    >
                </div>

                <div class="mb-2">
                    <label class="form-label">Método de Pago:</label>
                    <select id="metodo" class="form-control">
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TARJETA">Tarjeta</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Estado del Pago:</label>
                    <select id="estado_pago" class="form-control">
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="COMPLETADO">Completado</option>
                        </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Número de Operación / Boleta:</label>
                    <input 
                        type="text" 
                        id="operacion" 
                        class="form-control" 
                        placeholder="Ej: BOL-0001 o OP-12345"
                    >
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                
                <button type="button" class="btn btn-primary" id="guardarPago">
                    Guardar Pago
                </button>

                <button type="button" class="btn btn-success" id="actualizarPago" style="display:none;">
                    Actualizar Datos
                </button>
            </div>

        </div>
    </div>
</div>

<script src="../../assets/JS/pago_cita.js"></script>