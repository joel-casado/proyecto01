<?php
// Inicia la sesión (debe estar en la parte superior del archivo)
session_start();

// Verifica si la sesión del administrador no está activa
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Si no está activa, muestra un mensaje y redirige después de 4 segundos
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="4;url=login_admin_datos.php">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="administracion.scss">
        <title>No tienes permiso</title>
    </head>
    <body>
        <div class="container">
            <p>No tienes permiso para acceder a esta página.</p>
        </div>
    </body>
    </html>';
    exit; // Asegura que el script se detenga después de mostrar el mensaje
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="administracion.scss">
    <title>Tu Página</title>
</head>
<body>
    <div class="container">
        <div class="background"></div>
        <div class="main-box">
            <div class="title">
                <h2>Gestión de Cursos</h2>
            </div>
            <div class="content-box">
                <!-- Contenido dentro del primer div blanco -->
                <a href="crear_curso.php">Crear Curso</a>
                <a href="#">Modificar Curso</a>
                <a href="#">Eliminar Curso</a>
            </div>
            <div class="title">
                <h2>Gestión de Profesores</h2>
            </div>
            <div class="content-box">
                <!-- Contenido dentro del segundo div blanco -->
                <a href="#">Crear Profesor</a>
                <a href="#">Modificar Profesor</a>
                <a href="#">Eliminar Profesor</a>
            </div>
            <div class="logout-link">
                <a href="destruir_sesion.php" class="logout">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
