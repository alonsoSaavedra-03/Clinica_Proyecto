<?php
require_once "../config/conexion.php";

    $conn = $pdo; // Aseguramos que $conn esté definido para el resto del código

// HEADERS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");



try {

    $stmt = $conn->query("SELECT COUNT(*) as total FROM MEDICAMENTO");
    $totalMedicamentos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Contar medicamentos en estado crítico
    $stmt = $conn->query("
        SELECT COUNT(*) as criticos
        FROM MEDICAMENTO
        WHERE STOCK <= STOCK_CRITICO
    ");
    $criticos = $stmt->fetch(PDO::FETCH_ASSOC)['criticos'];

    // Contar categorías a mostrar en el dashboard
    $totalCategorias = 0;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM CATEGORIA");
        $totalCategorias = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(Exception $e){
        $totalCategorias = 0;
    }

    // Visualizar última fecha de movimiento en el kardex
    $stmt = $conn->query("SELECT MAX(FECHA) as ultima FROM KARDEX");
    $ultimaFecha = $stmt->fetch(PDO::FETCH_ASSOC)['ultima'];

    echo json_encode([
        "totalMedicamentos" => (int)$totalMedicamentos,
        "criticos" => (int)$criticos,
        "totalCategorias" => (int)$totalCategorias,
        "ultimaFecha" => $ultimaFecha
    ]);

} catch(Exception $e){

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}