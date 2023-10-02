<?php
// Inicio de sesión
session_start();

// Mira si el admin esta logado
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Si no lo está se espera 4 secs y se va
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="4;url=login_admin_datos.php">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/administracion.scss">
        <title>No tienes permiso</title>
    </head>
    <body>
        <div class="container">
            <p>No tienes permiso para acceder a esta página.</p>
        </div>
    </body>
    </html>';
    exit; // Exit para parar script
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/administracion.scss">
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
                <a href="cursos/crear_curso.php">Crear Curso</a>
                <a href="cursos/modificar_curso.php">Modificar Curso</a>
            </div>
            <div class="title">
                <h2>Gestión de Profesores</h2>
            </div>
            <div class="content-box">
                <!-- Contenido dentro del segundo div blanco -->
                <a href="profesores/crear_profesor.php">Crear Profesor</a>
                <a href="profesores/modificar_profesor.php">Modificar Profesor</a>
            </div>
            <div class="logout-link">
                <a href="../destruir_sesion.php" class="logout">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>