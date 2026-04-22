<?php
// PHP/api/pago_cita.php

require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';

// Headers para formato JSON y permisos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

/* ========================
   LISTAR PAGOS
======================== */
if($accion == "listar"){
    try {
        $sql = "SELECT 
                    P.ID_PAGO,
                    P.ID_CITA,
                    P.MONTO_TOTAL,
                    P.METODO_PAGO,
                    P.ESTADO_PAGO,
                    P.FECHA_PAGO,
                    P.NUMERO_OPERACION,
                    PAC.NOMBRES_PACIENTE, 
                    PAC.APELLIDOS_PACIENTE
                FROM PAGO_CITA P
                INNER JOIN CITA C ON P.ID_CITA = C.ID_CITA
                INNER JOIN PACIENTE PAC ON C.ID_PACIENTE = PAC.ID_PACIENTE
                ORDER BY P.FECHA_PAGO DESC, P.ID_PAGO DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

/* ========================
   REGISTRAR PAGO
======================== */
if($accion == "registrar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $id_cita   = $_POST['id_cita'] ?? null;
    $monto     = $_POST['monto'] ?? null;
    $metodo    = $_POST['metodo'] ?? null;
    $estado    = $_POST['estado'] ?? null;
    $operacion = $_POST['operacion'] ?? null;
    $fecha     = $_POST['fecha_pago'] ?? null; // Recibimos la fecha manual

    try {
        if(!$id_cita || !$monto || !$fecha){
            throw new Exception("Faltan datos: Cita, Monto y Fecha son obligatorios.");
        }

        $stmt = $pdo->prepare("
            INSERT INTO PAGO_CITA
            (ID_CITA, MONTO_TOTAL, METODO_PAGO, ESTADO_PAGO, NUMERO_OPERACION, FECHA_PAGO)
            VALUES(?,?,?,?,?,?)
        ");

        $stmt->execute([$id_cita, $monto, $metodo, $estado, $operacion, $fecha]);

        echo json_encode(["success" => true, "message" => "Pago registrado correctamente"]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

/* ========================
   EDITAR PAGO
======================== */
if($accion == "editar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $id_pago   = $_POST['id'] ?? null;
    $id_cita   = $_POST['id_cita'] ?? null;
    $monto     = $_POST['monto'] ?? null;
    $metodo    = $_POST['metodo'] ?? null;
    $estado    = $_POST['estado'] ?? null;
    $operacion = $_POST['operacion'] ?? null;
    $fecha     = $_POST['fecha_pago'] ?? null; // Recibimos la fecha manual corregida

    try {
        $sql = "UPDATE PAGO_CITA SET 
                    ID_CITA = ?, 
                    MONTO_TOTAL = ?, 
                    METODO_PAGO = ?, 
                    ESTADO_PAGO = ?, 
                    NUMERO_OPERACION = ?,
                    FECHA_PAGO = ?
                WHERE ID_PAGO = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_cita, $monto, $metodo, $estado, $operacion, $fecha, $id_pago]);

        echo json_encode(["success" => true]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

/* ========================
   ELIMINAR PAGO
======================== */
if($accion == "eliminar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $id = $_POST['id'] ?? null;

    try {
        $stmt = $pdo->prepare("DELETE FROM PAGO_CITA WHERE ID_PAGO = ?");
        $stmt->execute([$id]);

        echo json_encode(["success" => true]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>