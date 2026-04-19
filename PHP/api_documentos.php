<?php
require_once "../config/conexion.php";

try{

    $id = $_POST['id_paciente'];
    $tipo = $_POST['tipo'];
    $desc = $_POST['descripcion'];

    if(!isset($_FILES['archivo'])){
        throw new Exception("No se recibió archivo");
    }

    $archivo = $_FILES['archivo'];

    //limpiar el nombre del archivo y agregar timestamp para evitar colisiones
    $nombre = time() . "_" . preg_replace('/\s+/', '_', $archivo['name']);

    //ruta absoluta para guardar el archivo
    $carpeta = __DIR__ . "/../uploads/";

    // Crear la carpeta si no existe
    if(!file_exists($carpeta)){
        mkdir($carpeta, 0777, true);
    }

    $ruta = $carpeta . $nombre;

    // Mover archivo
    if(!move_uploaded_file($archivo['tmp_name'], $ruta)){
        throw new Exception("Error al guardar archivo");
    }

    // Ruta URL para acceder al archivo 
    $url = "/Clinica_Proyecto/uploads/" . $nombre;

    $stmt = $pdo->prepare("
        INSERT INTO DOCUMENTO
        (ID_PACIENTE, TIPO_DOCUMENTO, DESCRIPCION, ARCHIVO_URL)
        VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $id,
        $tipo,
        $desc,
        $url
    ]);

    // Respuesta exitosa
    echo json_encode([
        "success" => true,
        "url" => $url 
    ]);

}catch(Exception $e){
    echo json_encode([
        "error"=>$e->getMessage()
    ]);
}