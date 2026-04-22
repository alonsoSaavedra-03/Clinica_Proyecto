<?php
header('Content-Type: application/json');
include("../../config/conexion.php");

$data = [];

// 🔹 TOTAL PACIENTES
$sql1 = "SELECT COUNT(*) as total FROM PACIENTE";
$data['pacientes'] = $pdo->query($sql1)->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 CITAS HOY
$sql2 = "SELECT COUNT(*) as total FROM CITA 
         WHERE DATE(FECHA_CITA) = CURDATE()";
$data['citas_hoy'] = $pdo->query($sql2)->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 TOTAL EMPLEADOS
$sql3 = "SELECT COUNT(*) as total FROM EMPLEADO";
$data['empleados'] = $pdo->query($sql3)->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 PACIENTES POR MES (para gráfico línea)
$sql4 = "SELECT MONTH(FECHA_REGISTRO) as mes, COUNT(*) as total 
         FROM PACIENTE 
         GROUP BY mes";
// 🔹 TOTAL DE CITAS
$sql6 = "SELECT COUNT(*) as total FROM CITA";
$data['total_citas'] = $pdo->query($sql6)->fetch(PDO::FETCH_ASSOC)['total'];

$pacientesMes = array_fill(1, 12, 0);

foreach ($pdo->query($sql4) as $row) {
    $pacientesMes[(int)$row['mes']] = (int)$row['total'];
}

$data['pacientes_mes'] = array_values($pacientesMes);

// 🔹 CITAS POR ESTADO (para gráfico pastel)
$sql5 = "SELECT ESTADO, COUNT(*) as total FROM CITA GROUP BY ESTADO";

$estados = [];
$totales = [];

foreach ($pdo->query($sql5) as $row) {
    $estados[] = $row['ESTADO'];
    $totales[] = $row['total'];
}

$data['citas_estado'] = [
    'labels' => $estados,
    'data' => $totales
];

echo json_encode($data);