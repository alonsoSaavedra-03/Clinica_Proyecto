<?php
// Reporte de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ruta de conexión: Un nivel arriba desde PHP/ a la raíz
$ruta_conexion = "../config/conexion.php";

if (file_exists($ruta_conexion)) {
    require_once $ruta_conexion;
} else {
    header("Content-Type: application/json");
    die(json_encode(["error" => "No se encontro conexion.php"]));
}

// Headers obligatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$accion = $_GET['accion'] ?? '';

/**
 * CORRECCIÓN CLAVE:
 * Usamos 'empleado_id' porque es el nombre que definiste en tu login.php
 */
$id_sesion = $_SESSION['empleado_id'] ?? null;
$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'empleado';

/* ========================
   LISTAR DATOS DEL PERFIL
======================== */
if($accion == "listar"){
    try {
        if(!$id_sesion) {
            echo json_encode(["error" => "Sesion no encontrada. Inicie sesion de nuevo."]);
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

        echo json_encode($data ? $data : ["error" => "Usuario no encontrado en la DB"]);

    } catch(PDOException $e) {
        echo json_encode(["error" => "Error de Base de Datos: " . $e->getMessage()]);
    }
}

/* ========================
   SECCIÓN EDITAR
======================== */
if($accion == "editar" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    $correo   = $_POST['correo'] ?? null;
    $celular  = $_POST['celular'] ?? null;
    $password = $_POST['password'] ?? ''; 

    try {
        if(!$id_sesion) throw new Exception("No hay sesion activa.");

        if ($tipo_usuario == 'empleado') {
            if (!empty($password)) {
                // Usamos sha1() porque es lo que usa tu login.php
                $pass_hash = sha1($password);
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO=?, CELULAR_EMPLEADO=?, PASSWORD_HASH=? WHERE ID_EMPLEADO=?";
                $params = [$correo, $celular, $pass_hash, $id_sesion];
            } else {
                $sql = "UPDATE EMPLEADO SET CORREO_EMPLEADO=?, CELULAR_EMPLEADO=? WHERE ID_EMPLEADO=?";
                $params = [$correo, $celular, $id_sesion];
            }
        } else {
            if (!empty($password)) {
                $pass_hash = sha1($password);
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE=?, CELULAR_PACIENTE=?, PASSWORD_HASH=? WHERE ID_PACIENTE=?";
                $params = [$correo, $celular, $pass_hash, $id_sesion];
            } else {
                $sql = "UPDATE PACIENTE SET CORREO_PACIENTE=?, CELULAR_PACIENTE=? WHERE ID_PACIENTE=?";
                $params = [$correo, $celular, $id_sesion];
            }
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["success" => true]);

    } catch(Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
?>