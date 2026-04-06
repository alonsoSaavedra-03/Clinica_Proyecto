// Permitira usar la libreria de jQuery para manipular el DOM y manejar eventos de forma sencilla
$(document).ready(function () {

    $("#loginForm").submit(function (evento) {
    evento.preventDefault(); //<-- AJAX - Evitar el parapadeo de pantalla (Que se recargue la página al enviar el formulario)

  // Capturamos los datos ingresados por el Usuario en una variable
    let usuario = $("#txtUsuario").val();
    let password = $("#txtPassword").val();

    $.ajax({
        url: "../PHP/login.php",
        type: "POST",
        dataType: "json",
        data: {
        user: usuario,
        pass: password,
        },

        beforeSend: function () {
      // 1. Mensaje de carga mientras PHP consulta la Base de Datos
        Swal.fire({
        title: "Validando credenciales...",
        html: "Por favor, espere un momento.",
        allowOutsideClick: false,
        didOpen: () => {
        Swal.showLoading();
        },
        });

        },
        success: function (respuesta) {
        if (respuesta.exito) {
       // 2. CASO DE ÉXITO: Animación verde
        Swal.fire({
        icon: "success",
        title: "¡Acceso Concedido!",
        text: "Redirigiendo al sistema...",
        showConfirmButton: false,
        timer: 2000, // El popup se cierra solo en 2 segundos
        }).then(() => {
        // Esta redirección se ejecuta cuando el timer termina
        window.location.href = "../PAGES/dashboard.php";
        });

        } else {
       // 3. CASO DE ERROR: Animación roja (Credenciales inválidas)
        Swal.fire({
        icon: "error",
        title: "Acceso Denegado",
        text: "El usuario o la contraseña son incorrectos.",
        confirmButtonColor: "#d33", // Color rojo para el botón
        });
        }
        },

        error: function () {
      // 4. CASO DE ERROR CRÍTICO: Falla en el servidor o ruta
        Swal.fire({
        icon: "error",
        title: "¡Error de conexión!",
        text: "No se pudo conectar con el servidor.",
        confirmButtonColor: "#3085d6",
        });
        },
    });
    });
});
