<?php
require_once "../config/conexion.php";

$id = $_GET['id'];

try{

// RECETAS
$stmt = $pdo->prepare("
SELECT 
C.FECHA_CITA as fecha,
M.NOMBRE_MEDICAMENTO as medicamento,
R.DOSIS as dosis,
R.INDICACIONES as indicaciones
FROM RECETA R
JOIN CITA C ON R.ID_CITA = C.ID_CITA
JOIN MEDICAMENTO M ON R.ID_MEDICAMENTO = M.ID_MEDICAMENTO
WHERE C.ID_PACIENTE = ?
ORDER BY C.FECHA_CITA DESC
");
$stmt->execute([$id]);
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DOCUMENTOS
$stmt = $pdo->prepare("
SELECT 
TIPO_DOCUMENTO as tipo,
DESCRIPCION as descripcion,
ARCHIVO_URL as archivo
FROM DOCUMENTO
WHERE ID_PACIENTE = ?
ORDER BY FECHA_DOCUMENTO DESC
");
$stmt->execute([$id]);
$documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "recetas"=>$recetas,
    "documentos"=>$documentos
]);

}catch(Exception $e){
    echo json_encode(["error"=>$e->getMessage()]);
}