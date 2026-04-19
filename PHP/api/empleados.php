<?php

require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';

if($accion == "listar"){

$sql = "SELECT 
ID_EMPLEADO,
NOMBRES_EMPLEADO,
APELLIDOS_EMPLEADO
FROM EMPLEADO";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);

}