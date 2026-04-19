<div class="card shadow-sm p-4">

    <div class="d-flex justify-content-between mb-3">

        <h4 class="titulo">Gestión de Pacientes</h4>

        <button class="btn btn-primary" id="btnNuevoPaciente">
            Nuevo Paciente
        </button>

    </div>

    <table class="table table-hover">

        <thead>

            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Celular</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody id="tablaPacientes">

        </tbody>

    </table>

</div>



<div class="modal fade" id="modalPaciente">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5>Nuevo Paciente</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<input type="text" id="dni" class="form-control mb-2" placeholder="DNI">

<input type="text" id="nombre" class="form-control mb-2" placeholder="Nombres">

<input type="text" id="apellido" class="form-control mb-2" placeholder="Apellidos">

<input type="text" id="celular" class="form-control mb-2" placeholder="Celular">

<input type="date" id="fecha" class="form-control mb-2">

<select id="genero" class="form-control mb-2">
<option value="">Genero</option>
<option value="M">Masculino</option>
<option value="F">Femenino</option>
</select>

<input type="text" id="direccion" class="form-control mb-2" placeholder="Dirección">

<input type="email" id="correo" class="form-control mb-2" placeholder="Correo">

<hr>

<!-- DATOS DE ACCESO -->
<small class="text-muted">
Datos de acceso del paciente
</small>

<input type="text" id="username" class="form-control mb-2" placeholder="Username">

<input type="password" id="password" class="form-control mb-2" placeholder="Contraseña">

</div>

<div class="modal-footer">

<button class="btn btn-primary" id="guardarPaciente">
Guardar
</button>

</div>

</div>
</div>
</div>




<div class="modal fade" id="modalEditar">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5>Editar Paciente</h5>

                <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                <input type="hidden" id="idEditar">

                <input type="text" id="dniEditar" class="form-control mb-2" placeholder="DNI">

                <input type="text" id="nombreEditar" class="form-control mb-2" placeholder="Nombres">

                <input type="text" id="apellidoEditar" class="form-control mb-2" placeholder="Apellidos">

                <input type="text" id="celularEditar" class="form-control mb-2" placeholder="Celular">

                <input type="date" id="fechaEditar" class="form-control mb-2">

                <select id="generoEditar" class="form-control mb-2">
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                </select>

                <input type="text" id="direccionEditar" class="form-control mb-2">

                <input type="email" id="correoEditar" class="form-control mb-2">

                <hr>

                <small class="text-muted">
                Datos de acceso
                </small>

                <input type="text" id="usernameEditar" class="form-control mb-2">

                </div>

                <div class="modal-footer">

                <button class="btn btn-primary" id="actualizarPaciente">
                Actualizar
                </button>

            </div>

        </div>
    </div>
</div>

<script src="../../assets/JS/pacientes.js"></script>