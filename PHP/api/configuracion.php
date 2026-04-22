<?php
session_start();

/**
 * CORRECCIÓN DE RUTA: 
 * Como el archivo está en PHP/api/, 
 * subimos dos niveles (../../) para llegar a la raíz y entrar a config/
 */
require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';
$id_sesion = $_SESSION['id_usuario'] ?? null;
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'empleado';

header("Content-Type: application/json; charset=UTF-8");

/* ========================
   LISTAR DATOS DE PERFIL
======================== */
if($accion == "listar"){
    try {
        if(!$id_sesion) throw new Exception("No hay una sesión activa.");

        if ($tipo_usuario == 'empleado') {
            $sql = "SELECT ID_EMPLEADO AS ID, NOMBRES_EMPLEADO AS NOMBRES, APELLIDOS_EMPLEADO AS APELLIDOS, 
                    CORREO_EMPLEADO AS CORREO, CELULAR_EMPLEADO AS CELULAR, USERNAME, 
                    ESPECIALIDAD AS DETALLE FROM EMPLEADO WHERE ID_EMPLEADO = ?";
        } else {
            $sql = "SELECT ID_PACIENTE AS ID, NOMBRES_PACIENTE AS NOMBRES, APELLIDOS_PACIENTE AS APELLIDOS, 
                    CORREO_PACIENTE AS CORREO, CELULAR_PACIENTE AS CELULAR, USERNAME, 
                    'Paciente' AS DETALLE FROM PACIENTE WHERE ID_PACIENTE = ?";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_sesion]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($resultado);

    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

/* ========================
   EDITAR PERFIL (CONFIGURACIÓN)
======================== */
if($accion == "editar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $correo = $_POST['correo'] ?? null;
    $celular = $_POST['celular'] ?? null;
    $password = $_POST['password'] ?? ''; 

    try {
        if(!$correo || !$celular) throw new Exception("Faltan datos obligatorios.");

        if ($tipo_usuario == 'empleado') {
            if (!empty($password)) {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO = ?, CELULAR_EMPLEADO = ?, PASSWORD_HASH = ? WHERE ID_EMPLEADO = ?";
                $params = [$correo, $celular, $pass_hash, $id_sesion];
            } else {
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO = ?, CELULAR_EMPLEADO = ? WHERE ID_EMPLEADO = ?";
                $params = [$correo, $celular, $id_sesion];
            }
        } else {
            if (!empty($password)) {
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE = ?, CELULAR_PACIENTE = ?, PASSWORD_HASH = ? WHERE ID_PACIENTE = ?";
                $params = [$correo, $celular, $pass_hash, $id_sesion];
            } else {
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE = ?, CELULAR_PACIENTE = ? WHERE ID_PACIENTE = ?";
                $params = [$correo, $celular, $id_sesion];
            }
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(["success" => true, "message" => "Perfil actualizado"]);

    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>