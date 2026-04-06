<?php
// Credenciales de la Base de Datos
$host = 'localhost';
$db   = 'CLINICA';
$user = 'root';
$pass = '';

try {
    // Conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Datos enviados por POST (AJAX)
    $usuarioIngresado = $_POST['user'] ?? '';
    $passwordIngresada = $_POST['pass'] ?? '';

    $passwordHash = sha1($passwordIngresada);

    // Consulta
    $sql = "SELECT ID_EMPLEADO, PASSWORD_HASH 
            FROM EMPLEADO 
            WHERE USERNAME = :usuario 
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuarioIngresado);
    $stmt->execute();

    $usuarioFila = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validación
    if ($usuarioFila && $passwordHash === $usuarioFila['PASSWORD_HASH']) {

        session_start();
        $_SESSION['empleado_id'] = $usuarioFila['ID_EMPLEADO'];

        echo json_encode([
            "exito" => true,
            "mensaje" => "Login correcto"
        ], JSON_UNESCAPED_UNICODE);

    } else {

        echo json_encode([
            "exito" => false,
            "mensaje" => "Usuario o contraseña incorrectos."
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Error de conexión a la BD."
    ], JSON_UNESCAPED_UNICODE);
}
?>