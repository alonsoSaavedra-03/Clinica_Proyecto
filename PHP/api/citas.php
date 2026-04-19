<?php

require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';

/* ========================
   LISTAR
======================== */

if($accion == "listar"){

$sql = "SELECT

C.ID_CITA,
C.ID_PACIENTE,
C.ID_EMPLEADO,

P.NOMBRES_PACIENTE,
P.APELLIDOS_PACIENTE,

E.NOMBRES_EMPLEADO,
E.APELLIDOS_EMPLEADO,

C.FECHA_CITA,
C.MOTIVO,
C.ESTADO,
C.OBSERVACIONES

FROM CITA C

JOIN PACIENTE P 
ON C.ID_PACIENTE = P.ID_PACIENTE

JOIN EMPLEADO E
ON C.ID_EMPLEADO = E.ID_EMPLEADO";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);

}


/* ========================
   REGISTRAR
======================== */

if($accion == "registrar"){

$paciente = $_POST['paciente'];
$empleado = $_POST['empleado'];
$fecha = $_POST['fecha'];
$motivo = $_POST['motivo'];
$estado = $_POST['estado'];
$observaciones = $_POST['observaciones'];

$sql = "INSERT INTO CITA
(ID_PACIENTE,ID_EMPLEADO,FECHA_CITA,MOTIVO,ESTADO,OBSERVACIONES)
VALUES (?,?,?,?,?,?)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
$paciente,
$empleado,
$fecha,
$motivo,
$estado,
$observaciones
]);

echo json_encode(["success"=>true]);

}


/* ========================
   EDITAR
======================== */

if($accion == "editar"){

$id = $_POST['id'];
$paciente = $_POST['paciente'];
$empleado = $_POST['empleado'];
$fecha = $_POST['fecha'];
$motivo = $_POST['motivo'];
$estado = $_POST['estado'];
$observaciones = $_POST['observaciones'];

$sql = "UPDATE CITA SET

ID_PACIENTE = ?,
ID_EMPLEADO = ?,
FECHA_CITA = ?,
MOTIVO = ?,
ESTADO = ?,
OBSERVACIONES = ?

WHERE ID_CITA = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
$paciente,
$empleado,
$fecha,
$motivo,
$estado,
$observaciones,
$id
]);

echo json_encode(["success"=>true]);

}



/* ========================
   ELIMINAR
======================== */

if($accion == "eliminar"){

$id = $_POST['id'];

$sql = "DELETE FROM CITA WHERE ID_CITA = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

echo json_encode(["success"=>true]);

}