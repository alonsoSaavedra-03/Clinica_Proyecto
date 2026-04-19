<?php
require_once "../config/conexion.php";

$conn = $pdo; // Aseguramos que $conn esté definido para el resto del código

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//GET para listar losmedicamentos
if($_SERVER['REQUEST_METHOD'] == 'GET'){

    try{

        $stmt = $conn->query("
            SELECT 
                ID_MEDICAMENTO as id,
                NOMBRE_MEDICAMENTO as nombre,
                DESCRIPCION as descripcion,
                STOCK as stock,
                PRECIO as precio,
                FECHA_VENCIMIENTO as fecha_vencimiento,
                COALESCE(STOCK_MINIMO,10) as stock_minimo,
                COALESCE(STOCK_CRITICO,5) as stock_critico,
                LABORATORIO as laboratorio
            FROM MEDICAMENTO
        ");

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    }catch(Exception $e){
        echo json_encode(["error"=>$e->getMessage()]);
    }
}

//POST para guardar nuevo medicamento
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data = json_decode(file_get_contents("php://input"), true);

    try{

        $stmt = $conn->prepare("
            INSERT INTO MEDICAMENTO
            (NOMBRE_MEDICAMENTO, DESCRIPCION, STOCK, PRECIO, FECHA_VENCIMIENTO, STOCK_MINIMO, STOCK_CRITICO, LABORATORIO)
            VALUES(?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['stock'],
            $data['precio'],
            $data['fecha_vencimiento'],
            $data['stock_minimo'],
            $data['stock_critico'],
            $data['laboratorio']
        ]);

        echo json_encode(["status"=>"ok"]);

    }catch(Exception $e){
        echo json_encode(["error"=>$e->getMessage()]);
    }
}
?>
