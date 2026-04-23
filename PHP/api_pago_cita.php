<?php
// PHP/api_pago_cita.php

// 1. Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Conexión
require_once "../config/conexion.php";

// 3. Capturar acción
$accion = $_GET['accion'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'];

// 4. Headers para API JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

/* ========================
   LISTAR PAGOS (GET)
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
        echo json_encode(["error" => "Error al listar: " . $e->getMessage()]);
    }
}

/* ========================
   REGISTRAR PAGO (POST)
======================== */
elseif($accion == "registrar" && $metodo == 'POST'){
    
    $input = json_decode(file_get_contents('php://input'), true);

    $id_cita   = $input['ID_CITA'] ?? ($_POST['id_cita'] ?? null);
    $monto     = $input['MONTO_TOTAL'] ?? ($_POST['monto'] ?? null);
    $metodo_p  = $input['METODO_PAGO'] ?? ($_POST['metodo'] ?? null);
    $estado    = $input['ESTADO_PAGO'] ?? ($_POST['estado'] ?? null);
    $operacion = $input['NUMERO_OPERACION'] ?? ($_POST['operacion'] ?? null);
    $fecha     = $input['FECHA_PAGO'] ?? ($_POST['fecha_pago'] ?? null);

    try {
        if (empty($id_cita) || empty($monto) || empty($fecha)) {
            throw new Exception("Faltan datos obligatorios (Cita, Monto o Fecha).");
        }

        $stmt = $pdo->prepare("
            INSERT INTO PAGO_CITA
            (ID_CITA, MONTO_TOTAL, METODO_PAGO, ESTADO_PAGO, NUMERO_OPERACION, FECHA_PAGO)
            VALUES(?,?,?,?,?,?)
        ");
        $stmt->execute([$id_cita, $monto, $metodo_p, $estado, $operacion, $fecha]);

        // Buscar datos del paciente para la respuesta
        $sql_info = "SELECT PAC.NOMBRES_PACIENTE, PAC.APELLIDOS_PACIENTE 
                     FROM CITA C
                     INNER JOIN PACIENTE PAC ON C.ID_PACIENTE = PAC.ID_PACIENTE
                     WHERE C.ID_CITA = ?";
        $stmt_info = $pdo->prepare($sql_info);
        $stmt_info->execute([$id_cita]);
        $paciente = $stmt_info->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true, 
            "message" => "Pago registrado correctamente",
            "paciente" => $paciente ? $paciente['NOMBRES_PACIENTE'] . " " . $paciente['APELLIDOS_PACIENTE'] : "Desconocido"
        ]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

/* ========================
   EDITAR PAGO (POST)
======================== */
elseif($accion == "editar" && $metodo == 'POST'){
    
    $input = json_decode(file_get_contents('php://input'), true);
    $id_pago   = $input['ID_PAGO'] ?? ($_POST['id'] ?? null);
    $id_cita   = $input['ID_CITA'] ?? ($_POST['id_cita'] ?? null);
    $monto     = $input['MONTO_TOTAL'] ?? ($_POST['monto'] ?? null);
    $metodo_p  = $input['METODO_PAGO'] ?? ($_POST['metodo'] ?? null);
    $estado    = $input['ESTADO_PAGO'] ?? ($_POST['estado'] ?? null);
    $operacion = $input['NUMERO_OPERACION'] ?? ($_POST['operacion'] ?? null);
    $fecha     = $input['FECHA_PAGO'] ?? ($_POST['fecha_pago'] ?? null);

    try {
        if(!$id_pago) throw new Exception("ID de pago no proporcionado.");

        $sql = "UPDATE PAGO_CITA SET 
                    ID_CITA = ?, MONTO_TOTAL = ?, METODO_PAGO = ?, 
                    ESTADO_PAGO = ?, NUMERO_OPERACION = ?, FECHA_PAGO = ?
                WHERE ID_PAGO = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_cita, $monto, $metodo_p, $estado, $operacion, $fecha, $id_pago]);

        echo json_encode(["success" => true, "message" => "Pago actualizado"]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

/* ========================
   ELIMINAR PAGO 
======================== */
elseif($accion == "eliminar" && ($metodo == 'POST' || $metodo == 'DELETE')){
    
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['ID_PAGO'] ?? ($_POST['id'] ?? null);

    try {
        if(!$id) throw new Exception("ID de pago no enviado.");

        $stmt = $pdo->prepare("DELETE FROM PAGO_CITA WHERE ID_PAGO = ?");
        $stmt->execute([$id]);

        echo json_encode([
            "success" => true, 
            "message" => "Pago con ID $id eliminado correctamente."
        ]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}

/* ========================
   CASO POR DEFECTO
======================== */
else {
    http_response_code(404);
    echo json_encode([
        "error" => "Acción no válida o método incorrecto.",
        "recibido" => [
            "accion" => $accion,
            "metodo" => $metodo
        ]
    ]);
}
?>