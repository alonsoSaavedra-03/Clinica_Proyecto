<?php

require_once "../../config/conexion.php";

$accion = $_GET['accion'] ?? '';

/* ========================
   LISTAR PACIENTES
======================== */

if($accion == "listar"){

    $sql = "SELECT * FROM PACIENTE";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

}


/* ========================
   REGISTRAR PACIENTE
======================== */

if($accion == "registrar"){

    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $celular = $_POST['celular'];
    $fecha = $_POST['fecha'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    $edad = date_diff(date_create($fecha), date_create('today'))->y;

    $sql = "INSERT INTO PACIENTE
    (DNI_PACIENTE,NOMBRES_PACIENTE,APELLIDOS_PACIENTE,
    FECHA_NACIMIENTO,EDAD_PACIENTE,GENERO_PACIENTE,
    DIRECCION_PACIENTE,CELULAR_PACIENTE,CORREO_PACIENTE,
    USERNAME,PASSWORD_HASH)

    VALUES (?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $dni,
        $nombre,
        $apellido,
        $fecha,
        $edad,
        $genero,
        $direccion,
        $celular,
        $correo,
        $username,
        $password
    ]);

    echo json_encode(["success"=>true]);

}

/* ========================
   EDITAR PACIENTE
======================== */

if($accion == "editar"){

    $id = $_POST['id'];
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $celular = $_POST['celular'];
    $fecha = $_POST['fecha'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $username = $_POST['username'];

    $edad = date_diff(date_create($fecha), date_create('today'))->y;

    $sql = "UPDATE PACIENTE SET

    DNI_PACIENTE = ?,
    NOMBRES_PACIENTE = ?,
    APELLIDOS_PACIENTE = ?,
    CELULAR_PACIENTE = ?,
    FECHA_NACIMIENTO = ?,
    EDAD_PACIENTE = ?,
    GENERO_PACIENTE = ?,
    DIRECCION_PACIENTE = ?,
    CORREO_PACIENTE = ?,
    USERNAME = ?

    WHERE ID_PACIENTE = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $dni,
        $nombre,
        $apellido,
        $celular,
        $id
    ]);

    echo json_encode(["success"=>true]);

}



/* ========================
   ELIMINAR PACIENTE
======================== */

if($accion == "eliminar"){

    $id = $_POST['id'];

    $sql = "DELETE FROM PACIENTE WHERE ID_PACIENTE = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

    echo json_encode(["success"=>true]);

}
?>