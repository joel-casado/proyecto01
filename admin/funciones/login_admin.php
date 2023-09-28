<?php
session_start();

// conexión a la base de datos
$mysqli = new mysqli("localhost", "admin1", "admin", "learning_academy");

// mira si hay error
if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}

// Coge los datos del form
$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];

// Verifica si las credenciales son correctas (usuario y contraseña coinciden)
if ($usuario === "admin1" && $contrasena === "admin") {

    // Guarda el estado de inicio de sesión en una variable de sesion
    $_SESSION["admin_logged_in"] = true;

    // Redirige a la página de administración
    header("Location: ../administracion.php");
    exit; // Asegura que el script se detenga después de la redirección
} else {
    // Inicio de sesión fallido
    // Redirige de nuevo al formulario de inicio de sesión con un mensaje de error
    header("Location: ../login_admin_datos.php?error=contrasena");
    exit;
}

// Cierra la conexión a la base de datos
$mysqli->close();
?>
