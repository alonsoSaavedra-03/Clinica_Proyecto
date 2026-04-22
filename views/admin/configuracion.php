<div class="card shadow-sm p-4">
    
    <div class="d-flex justify-content-between mb-3">
        <h4 class="titulo">Mi Configuración</h4>
        <button class="btn btn-primary" id="btnEditarPerfil">
            <i class="fas fa-user-edit"></i> Editar Perfil
        </button>
    </div>

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th colspan="2" class="table-light">Información Personal</th>
            </tr>
        </thead>
        <tbody id="datosUsuario">
            <tr>
                <th style="width: 30%;">Nombres y Apellidos:</th>
                <td id="conf-nombre-completo">Cargando...</td>
            </tr>
            <tr>
                <th>Nombre de Usuario:</th>
                <td id="conf-username">Cargando...</td>
            </tr>
            <tr>
                <th>Correo Electrónico:</th>
                <td id="conf-correo">Cargando...</td>
            </tr>
            <tr>
                <th>Celular:</th>
                <td id="conf-celular">Cargando...</td>
            </tr>
            <tr>
                <th>Especialidad / Rol:</th>
                <td id="conf-especialidad">Cargando...</td>
            </tr>
        </tbody>
    </table>

</div>

<div class="modal fade" id="modalConfig">
    
    <div class="modal-dialog">
        
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 id="tituloModalConfig">Actualizar Mis Datos</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="idUsuarioConf">

                <label class="small mb-1">Correo Electrónico</label>
                <input type="email" id="correoConf" class="form-control mb-2" placeholder="Correo">

                <label class="small mb-1">Celular</label>
                <input type="text" id="celularConf" class="form-control mb-2" placeholder="Celular">

                <hr>
                <h6 class="text-danger">Cambiar Contraseña</h6>
                
                <label class="small mb-1">Nueva Contraseña</label>
                <input type="password" id="passNueva" class="form-control mb-2" placeholder="Dejar en blanco para no cambiar">
                
                <label class="small mb-1">Confirmar Contraseña</label>
                <input type="password" id="passConfirmar" class="form-control" placeholder="Repita la contraseña">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-success" id="btnActualizarPerfil">
                    Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>

<script src="../../assets/JS/configuracion.js"></script>