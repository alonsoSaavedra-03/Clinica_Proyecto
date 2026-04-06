<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <!-- FRAMEWORK DE BOOTSTRAP -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- LIBRERIA DE ICONOS -->
      <script src="https://kit.fontawesome.com/812c8ee19a.js" crossorigin="anonymous"></script>

    <!-- FAVICON DE LA APLICACIÓN DE Clinica -->
     <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"> <!-- buscar una imagen de logotipo en ppts  -->

    <!-- FUENTES DE GOOGLE FONTS  --> <!-- puedo cambiarlo en google fonts -->
     <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <!-- HOJA DE ESTILOS  -->
     <link rel="stylesheet" href="../CSS/dashboard.css" >

    <!-- LIBRERIA JQUERY -->  
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
     
</head>
<body>
<div class="d-flex">
        <nav id="sidebar" class="p-3">
            <img class="imagen" src="../Imagenes/logo.png">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <button class="nav-link btn w-100 text-start panel" >Inicio</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn w-100 text-start panel" >Pacientes</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn w-100 text-start panel" >Citas</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn w-100 text-start panel" >Gestión de Medicamentos</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn w-100 text-start panel" >Gestión de Documentos</button>
                </li>
                <li class="nav-item">
                    <a href="../PHP/logout.php">
                    <button class="nav-link-logout btn w-100 text-start logout" >Cerrar Sesión</button>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="navbar navbar-light bg-white shadow-sm p-3">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1 titulo">Panel Administrativo</span>
                    
                    <form class="d-flex ms-auto me-3">
                        <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search">
                    </form>
                    
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-primary">
                            Usuario Conectado
                        </span>
                    </div>
                </div>
            </header>

            <main class="p-4" id="vista-dinamica">
                <div class="card p-5 shadow-sm text-center ">
                    <h2 class="ola">Bienvenido al Sistema de la Clínica</h2>
           
                </div>
            </main>
        </div>
    </div>








  <!-- LIBRERIA JS SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- LIBRERIA JS DE BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>