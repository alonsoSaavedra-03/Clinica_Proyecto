<?php
$NombrePersonal = $_SESSION['nombres_empleado'] ?? 'Usuario';
?>
<div class="dashboard-inicio">

    <!-- BIENVENIDA -->
    <div class="card bienvenida-card mb-4">
        <h2 class="bienvenida-titulo">Bienvenido, <span class="fw-bold" id="nombrePersonal"></span></h2>
        <p class="bienvenida-texto">
            Aquí tienes un resumen del estado actual de la clínica.
        </p>
    </div>

    <!-- RESUMEN -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <h6>Pacientes</h6>
                <h3>120</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <h6>Citas Hoy</h6>
                <h3>25</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <h6>Documentos</h6>
                <h3>80</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <h6>Medicamentos</h6>
                <h3>50</h3>
            </div>
        </div>

    </div>

    <!-- ACTIVIDAD RECIENTE -->
    <div class="card actividad-card mt-4">
        <div class="actividad-header">
            <h5>Actividad reciente</h5>
        </div>

        <ul class="lista-actividad">
            <li>Se registró un nuevo paciente</li>
            <li>Se agendó una nueva cita</li>
            <li>Se actualizó un medicamento</li>
        </ul>
    </div>

</div>