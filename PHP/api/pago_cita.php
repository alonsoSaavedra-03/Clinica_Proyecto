<?php
require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';

/* ========================
   LISTAR PAGOS
======================== */
if($accion == "listar"){
    // Usamos JOIN para traer info del paciente y médico a través de la CITA
    $sql = "SELECT 
                PG.ID_PAGO,
                PG.ID_CITA,
                PG.MONTO_TOTAL,
                PG.METODO_PAGO,
                PG.ESTADO_PAGO,
                PG.FECHA_PAGO,
                PG.NUMERO_OPERACION,
                P.NOMBRES_PACIENTE, 
                P.APELLIDOS_PACIENTE,
                E.NOMBRES_EMPLEADO, 
                E.APELLIDOS_EMPLEADO
            FROM PAGO_CITA PG
            JOIN CITA C ON PG.ID_CITA = C.ID_CITA
            JOIN PACIENTE P ON C.ID_PACIENTE = P.ID_PACIENTE
            JOIN EMPLEADO E ON C.ID_EMPLEADO = E.ID_EMPLEADO
            ORDER BY PG.FECHA_PAGO DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultado);
}

/* ========================
   REGISTRAR PAGO
======================== */
if($accion == "registrar"){
    $id_cita   = $_POST['id_cita'];
    $monto     = $_POST['monto'];
    $metodo    = $_POST['metodo'];
    $estado    = $_POST['estado'];
    $operacion = $_POST['operacion'];
    $fecha     = $_POST['fecha_pago']; // Capturamos la fecha manual del modal

    // Añadimos FECHA_PAGO a la consulta para que use la que tú envías
    $sql = "INSERT INTO PAGO_CITA 
            (ID_CITA, MONTO_TOTAL, METODO_PAGO, ESTADO_PAGO, NUMERO_OPERACION, FECHA_PAGO) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $id_cita,
        $monto,
        $metodo,
        $estado,
        $operacion,
        $fecha
    ]);

    echo json_encode(["success" => true]);
}

/* ========================
   EDITAR PAGO
======================== */
if($accion == "editar"){
    $id_pago   = $_POST['id'];
    $id_cita   = $_POST['id_cita'];
    $monto     = $_POST['monto'];
    $metodo    = $_POST['metodo'];
    $estado    = $_POST['estado'];
    $operacion = $_POST['operacion'];
    $fecha     = $_POST['fecha_pago']; // Capturamos la fecha manual para actualizarla

    $sql = "UPDATE PAGO_CITA SET 
                ID_CITA = ?, 
                MONTO_TOTAL = ?, 
                METODO_PAGO = ?, 
                ESTADO_PAGO = ?, 
                NUMERO_OPERACION = ?,
                FECHA_PAGO = ? 
            WHERE ID_PAGO = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $id_cita,
        $monto,
        $metodo,
        $estado,
        $operacion,
        $fecha,
        $id_pago
    ]);

    echo json_encode(["success" => true]);
}

/* ========================
   ELIMINAR PAGO
======================== */
if($accion == "eliminar"){
    $id = $_POST['id'];
    $sql = "DELETE FROM PAGO_CITA WHERE ID_PAGO = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
}
?>