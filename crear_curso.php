<?php
// Inicia la sesión
session_start();

// Mira que la sesion admin est activa
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
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
    exit;
}

// Verificación del envío de formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtención de datos de formulario.
    $codigo = $_POST["codigo"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $horas = $_POST["horas"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];

    // Validacion de datos. El código debe tener 3 digitos y la fecha de inicio no puede ser posterior a la fecha de finalización.
    if (!preg_match('/^\d{3}$/', $codigo)) {
        $mensaje = "El código debe tener exactamente tres dígitos.";
    } elseif (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
        $mensaje = "La fecha de inicio no puede ser posterior a la fecha de finalización.";
    } else {
        // Conexión a la base de datos
        $mysqli = new mysqli("localhost", "admin1", "admin", "learning_academy");

        if ($mysqli->connect_error) {
            die("Error en la conexión: " . $mysqli->connect_error);
        }

        // Verifica si el codigo existe en la base de datos
        $sql = "SELECT * FROM Cursos WHERE Codigo = '$codigo'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $mensaje = "El código del curso ya existe en la base de datos. Por favor, elige otro.";
        } else {
            // Inserta los datos del nuevo curso en la base de datos
            $sql = "INSERT INTO Cursos (Codigo, Nombre, Descripcion, Horas, Fecha_inicio, Fecha_fin) VALUES ('$codigo', '$nombre', '$descripcion', $horas, '$fecha_inicio', '$fecha_fin')";

            if ($mysqli->query($sql) === TRUE) {
            	// Redirige al usuario a la página de administración
                echo '<!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="refresh" content="3;url=administracion.php">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="administracion.scss">
                    <title>Curso añadido exitosamente</title>
                </head>
                <body>
                    <div class="container">
                        <p>Curso añadido exitosamente.</p>
                    </div>
                </body>
                </html>';
            	exit;
                $mensaje = "Error al crear el curso: " . $mysqli->error;
            }
        }

        // Cierra la conexión a la base de datos
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="crear_profesor.scss">
    <title>Crear Curso</title>
</head>
<body>
    <div class="container">
        <h2>Crear Curso</h2>
        <div class="mensaje"><?php if (isset($mensaje)) echo $mensaje; ?></div>
        <form action="crear_curso.php" method="POST">
            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="horas">Horas:</label>
            <input type="number" id="horas" name="horas" required>

            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Finalización:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <input type="submit" value="Crear Curso">
        </form>
    </div>
    <div class="return-button">
        <a href="administracion.php">Volver atrás</a>
    </div>
</body>
</html>