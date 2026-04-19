<?php
require_once "../config/conexion.php";

$conn = $pdo; // Aseguramos que $conn esté definido para el resto del código

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//validamos que el método sea POST
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

//validamos que vengan los datos necesarios
if(
    !isset($data['id_medicamento']) ||
    !isset($data['tipo']) ||
    !isset($data['cantidad'])
){
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$id = $data['id_medicamento'];
$tipo = strtoupper($data['tipo']);
$cantidad = (int)$data['cantidad'];
$motivo = isset($data['motivo']) ? $data['motivo'] : "";

if($cantidad <= 0){
    echo json_encode(["error" => "Cantidad inválida"]);
    exit;
}

if($tipo !== "ENTRADA" && $tipo !== "SALIDA"){
    echo json_encode(["error" => "Tipo inválido"]);
    exit;
}

$conn->beginTransaction();

try {

    //bloqueamos el medicamento para evitar condiciones de carrera
    $stmt = $conn->prepare("
        SELECT STOCK 
        FROM MEDICAMENTO 
        WHERE ID_MEDICAMENTO = ? 
        FOR UPDATE
    ");
    $stmt->execute([$id]);
    $med = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$med){
        throw new Exception("Medicamento no encontrado");
    }

    $stockActual = (int)$med['STOCK'];

    // calculamos nuevo stock según tipo de movimiento
    if($tipo === "SALIDA"){

        if($stockActual < $cantidad){
            throw new Exception("Stock insuficiente");
        }

        $nuevoStock = $stockActual - $cantidad;

    } else { //entrada

        $nuevoStock = $stockActual + $cantidad;
    }

    //actualizamos stock en la tabla de medicamentos
    $update = $conn->prepare("
        UPDATE MEDICAMENTO 
        SET STOCK = ? 
        WHERE ID_MEDICAMENTO = ?
    ");
    $update->execute([$nuevoStock, $id]);

    //Insertar movimiento en el kardex
    $insert = $conn->prepare("
        INSERT INTO KARDEX
        (ID_MEDICAMENTO, TIPO, CANTIDAD, SALDO_FINAL, MOTIVO)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->execute([
        $id,
        $tipo,
        $cantidad,
        $nuevoStock,
        $motivo
    ]);

    $conn->commit();

    echo json_encode([
        "status" => "ok",
        "stock_anterior" => $stockActual,
        "stock_nuevo" => $nuevoStock
    ]);

} catch(Exception $e){

    $conn->rollBack();

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>