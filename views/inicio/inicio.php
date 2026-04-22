<?php
$NombrePersonal = $_SESSION['nombres_empleado'] ?? 'Usuario';
?>

<div class="dashboard-inicio">

    <!-- BIENVENIDA -->
    <div class="card bienvenida-card mb-4">
        <h2 class="bienvenida-titulo">
            Bienvenido, <span class="fw-bold" id="nombrePersonal"></span>
        </h2>
        <p class="bienvenida-texto">
            Aquí tienes un resumen en tiempo real de la clínica.
        </p>
    </div>

    <!-- RESUMEN -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card stat-card text-center">
                <h6>Pacientes</h6>
                <h3 id="totalPacientes">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card text-center">
                <h6>Citas Hoy</h6>
                <h3 id="citasHoy">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card text-center">
                <h6>Empleados</h6>
                <h3 id="totalEmpleados">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card text-center">
                <h6>Total Citas</h6>
                <h3 id="totalCitas">0</h3>
            </div>
        </div>

    </div>

    <!-- GRÁFICOS -->
    <div class="row g-4">

        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="text-center">Pacientes por mes</h6>
                <canvas id="graficoLinea"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="text-center">Estado de citas</h6>
                <canvas id="graficoPastel"></canvas>
            </div>
        </div>

    </div>

    <!-- ACTIVIDAD -->
    <div class="card actividad-card mt-4">
        <div class="actividad-header">
            <h5>Actividad reciente</h5>
        </div>

        <ul class="lista-actividad" id="actividadLista">
            <li>Cargando actividad...</li>
        </ul>
    </div>

</div>