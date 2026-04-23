<?php
// PHP/api_configuracion.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../config/conexion.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$accion = $_GET['accion'] ?? 'listar'; // Si no hay acción, por defecto busca 'listar'

// Recuperamos la sesión para que tu módulo individual siga funcionando
$id_sesion = $_SESSION['empleado_id'] ?? null; 
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'empleado';

/* ============================================================
   1. LISTAR TODOS (Para ver a todos en Postman o Admin)
============================================================ */
if($accion == "listar_todos"){
    try {
        $sql_emp = "SELECT 'Empleado' AS TIPO, ID_EMPLEADO AS ID, NOMBRES_EMPLEADO AS NOMBRES, 
                    USERNAME, PASSWORD_HASH, CORREO_EMPLEADO AS CORREO FROM EMPLEADO";
        $stmt_emp = $pdo->query($sql_emp);
        $empleados = $stmt_emp->fetchAll(PDO::FETCH_ASSOC);

        $sql_pac = "SELECT 'Paciente' AS TIPO, ID_PACIENTE AS ID, NOMBRES_PACIENTE AS NOMBRES, 
                    USERNAME, PASSWORD_HASH, CORREO_PACIENTE AS CORREO FROM PACIENTE";
        $stmt_pac = $pdo->query($sql_pac);
        $pacientes = $stmt_pac->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array_merge($empleados, $pacientes));
        exit;
    } catch(PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}

/* ============================================================
   2. LISTAR PERFIL (Necesario para que tu módulo no de Error AJAX)
============================================================ */
if($accion == "listar" || empty($accion)){
    try {
        // Si no hay sesión, devolvemos un objeto vacío en lugar de un error de texto
        if (!$id_sesion) {
            echo json_encode(["error" => "No hay sesión activa"]);
            exit;
        }

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
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($data ? $data : ["error" => "Usuario no encontrado"]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
}

/* ============================================================
   3. EDITAR (Soporta JSON y Formularios tradicionales)
============================================================ */
if($accion == "editar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    // 1. Intentar leer como JSON (Postman)
    $input = json_decode(file_get_contents('php://input'), true);
    
    // 2. Si no es JSON, intentar leer de $_POST (Formulario Web)
    $id_target   = $input['ID']       ?? $_POST['id']       ?? $id_sesion;
    $tipo_target = $input['TIPO']     ?? $_POST['tipo']     ?? $tipo_usuario;
    $correo      = $input['CORREO']   ?? $_POST['correo']   ?? null;
    $celular     = $input['CELULAR']  ?? $_POST['celular']  ?? null;
    $password    = $input['PASSWORD'] ?? $_POST['password'] ?? '';

    try {
        // VALIDACIÓN: Si los campos llegan vacíos desde la web, NO sobreescribir con NULL
        // Solo actualizamos si el campo tiene contenido
        if (strtolower($tipo_target) == 'empleado') {
            if (!empty($password)) {
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO=?, CELULAR_EMPLEADO=?, PASSWORD_HASH=? WHERE ID_EMPLEADO=?";
                $params = [$correo, $celular, sha1($password), $id_target];
            } else {
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO=?, CELULAR_EMPLEADO=? WHERE ID_EMPLEADO=?";
                $params = [$correo, $celular, $id_target];
            }
        } else {
            if (!empty($password)) {
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE=?, CELULAR_PACIENTE=?, PASSWORD_HASH=? WHERE ID_PACIENTE=?";
                $params = [$correo, $celular, sha1($password), $id_target];
            } else {
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE=?, CELULAR_PACIENTE=? WHERE ID_PACIENTE=?";
                $params = [$correo, $celular, $id_target];
            }
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(["success" => true, "message" => "Actualizado correctamente"]);
    } catch(Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>