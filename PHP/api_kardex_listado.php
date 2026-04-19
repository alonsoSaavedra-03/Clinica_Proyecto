<?php
require_once "../config/conexion.php";

$conn = $pdo; // Aseguramos que $conn esté definido para el resto del código

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    //listamos movimientos del kardex con su respectivo nombre de medicamento
    $stmt = $conn->query("
        SELECT 
            k.FECHA as fecha,
            k.TIPO as tipo,
            m.NOMBRE_MEDICAMENTO as nombre,
            k.CANTIDAD as cantidad,
            k.SALDO_FINAL as saldo,
            k.MOTIVO as motivo
        FROM KARDEX k
        INNER JOIN MEDICAMENTO m 
            ON k.ID_MEDICAMENTO = m.ID_MEDICAMENTO
        ORDER BY k.FECHA DESC
    ");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} catch(Exception $e){
    echo json_encode(["error" => $e->getMessage()]);
}