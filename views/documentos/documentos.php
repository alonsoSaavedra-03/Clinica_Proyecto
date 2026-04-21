<div class="container-fluid mt-4">

  <!-- BUSCADOR -->
  <div class="mb-3">
    <input type="text" id="buscadorPacienteDocumentos" class="form-control"
           placeholder="Buscar por DNI o nombre...">
  </div>

  <!-- TABLA PACIENTES -->
  <div class="table-responsive">
    <table class="table table-hover">
      <thead class="table-dark">
        <tr>
          <th>DNI</th>
          <th>Nombre</th>
          <th>Celular</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tablaPacientesDocumentos"></tbody>
    </table>
  </div>

</div>

<!-- MODAL EXPEDIENTE -->
<div class="modal fade" id="modalExpediente" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 id="tituloPaciente"></h5>
      </div>

      <div class="modal-body">

        <!-- TABS -->
        <ul class="nav nav-tabs" id="tabsExpediente">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#recetasTab">
              Recetas
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documentosTab">
              Documentos
            </button>
          </li>
        </ul>

        <div class="tab-content mt-3">

          <!-- RECETAS -->
          <div class="tab-pane fade show active" id="recetasTab">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Medicamento</th>
                    <th>Dosis</th>
                    <th>Indicaciones</th>
                  </tr>
                </thead>
                <tbody id="tablaRecetas"></tbody>
              </table>
            </div>
          </div>

          <!-- DOCUMENTOS -->
          <div class="tab-pane fade" id="documentosTab">

            <ul class="list-group mb-3" id="listaDocumentos"></ul>

            <!-- FORM SUBIR -->
            <form id="formDocumento" enctype="multipart/form-data">
              <input type="hidden" id="docPacienteId" name="id_paciente">

              <select class="form-control mb-2" name="tipo" required>
                <option value="">Tipo de documento</option>
                <option>Examen</option>
                <option>Diagnostico</option>
                <option>Receta</option>
                <option>Informe</option>
              </select>

              <input type="text" class="form-control mb-2" name="descripcion" placeholder="Descripción">

              <input type="file" class="form-control mb-2" name="archivo" required>

              <button type="submit" class="btn btn-success w-100">
                Subir Documento
              </button>
            </form>

          </div>

        </div>

      </div>

    </div>
  </div>
</div>
