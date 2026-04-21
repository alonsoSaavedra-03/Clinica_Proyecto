<?php
// PHP/api/pago_cita.php

// 1. Conexión (Subimos dos niveles para llegar a la raíz desde api/ y php/)
require_once "../../config/conexion.php";

// 2. Definir la acción (listar, registrar, editar, eliminar)
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
        // Mantenemos tu JOIN pero normalizamos los nombres de las columnas para el JS
        $sql = "SELECT 
                    P.ID_PAGO,
                    P.ID_CITA,
                    P.MONTO_TOTAL,
                    P.METODO_PAGO,
                    P.ESTADO_PAGO,
                    P.FECHA_PAGO,
                    P.NUMERO_OPERACION,
                    PAC.NOMBRES_PACIENTE, 
                    PAC.APELLIDOS_PACIENTE,
                    C.FECHA_CITA
                FROM PAGO_CITA P
                INNER JOIN CITA C ON P.ID_CITA = C.ID_CITA
                INNER JOIN PACIENTE PAC ON C.ID_PACIENTE = PAC.ID_PACIENTE
                ORDER BY P.ID_PAGO DESC";

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
    
    // Capturamos los datos desde $_POST (enviados por el $.ajax)
    $id_cita   = $_POST['id_cita'] ?? null;
    $monto     = $_POST['monto'] ?? null;
    $metodo    = $_POST['metodo'] ?? null;
    $estado    = $_POST['estado'] ?? null;
    $operacion = $_POST['operacion'] ?? null;

    try {
        if(!$id_cita || !$monto){
            throw new Exception("Datos obligatorios faltantes (Cita o Monto)");
        }

        $stmt = $pdo->prepare("
            INSERT INTO PAGO_CITA
            (ID_CITA, MONTO_TOTAL, METODO_PAGO, ESTADO_PAGO, NUMERO_OPERACION)
            VALUES(?,?,?,?,?)
        ");

        $stmt->execute([$id_cita, $monto, $metodo, $estado, $operacion]);

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

    try {
        $sql = "UPDATE PAGO_CITA SET 
                    ID_CITA = ?, 
                    MONTO_TOTAL = ?, 
                    METODO_PAGO = ?, 
                    ESTADO_PAGO = ?, 
                    NUMERO_OPERACION = ? 
                WHERE ID_PAGO = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_cita, $monto, $metodo, $estado, $operacion, $id_pago]);

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