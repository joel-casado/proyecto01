<?php
// Inicia la sesión
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

// Verificación del envío de formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// Obtención de datos de formulario.
	$dni = $_POST["dni"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$titulacion = $_POST["titulacion"];
	$codigo_curso = $_POST["codigo_curso"]; // Nuevo campo para el código de curso

	// Validación de datos. El DNI debe tener 9 caracteres y no debe existir en la base de datos.
	if (strlen($dni) !== 9) {
    	$mensaje = "El DNI debe tener 9 caracteres.";
	} else {
    	// Establece la conexión a la base de datos
    	$mysqli = new mysqli("localhost", "admin1", "admin", "learning_academy");

    	// Verifica errores en la conexión
    	if ($mysqli->connect_error) {
        	die("Error en la conexión: " . $mysqli->connect_error);
    	}

    	// Verifica si el DNI ya existe en la base de datos
    	$sql = "SELECT * FROM Profesores WHERE DNI = '$dni'";
    	$result = $mysqli->query($sql);

    	if ($result->num_rows > 0) {
        	$mensaje = "El DNI del profesor ya existe en la base de datos. Por favor, elige otro.";
    	} else {
        	// Verifica si el código de curso existe en la tabla de cursos
        	$sql = "SELECT * FROM Cursos WHERE Codigo = '$codigo_curso'";
        	$result = $mysqli->query($sql);

        	if ($result->num_rows === 0) {
            	$mensaje = "El código de curso no existe en la base de datos. Por favor, verifica el código del curso.";
        	} else {
            	// Subir la imagen del profesor (opcional)
            	if ($_FILES["imagen"]["error"] === 0) {
                	// Directorio de destino para las imágenes de profesores
                	$uploadDir = "imagenes_profesores/";

                	// Generar un nombre de archivo único
                	$nombreArchivo = uniqid() . "_" . $_FILES["imagen"]["name"];

                	// Ruta completa del archivo
                	$rutaArchivo = $uploadDir . $nombreArchivo;

                	// Mover el archivo cargado al directorio de destino
                	if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
                    	// Insertar los datos del nuevo profesor en la base de datos
                    	$sql = "INSERT INTO Profesores (DNI, Nombre, Apellidos, Titulacion, Fotografia, estatus) VALUES ('$dni', '$nombre', '$apellidos', '$titulacion', '$rutaArchivo', 'activo')";

                    	if ($mysqli->query($sql) === TRUE) {
                        	// Actualizar la tabla de cursos con el DNI del profesor
                        	$sql = "UPDATE Cursos SET fk_profesor = '$dni' WHERE Codigo = '$codigo_curso'";
                        	if ($mysqli->query($sql) === TRUE) {
                            	// Redirige al usuario a la página de administración
                            	echo '<!DOCTYPE html>
                            	<html lang="es">
                            	<head>
                                	<meta charset="UTF-8">
                                	<meta http-equiv="refresh" content="3;url=administracion.php">
                                	<meta name="viewport" content="width=device-width, initial-scale=1.0">
                                	<link rel="stylesheet" href="administracion.scss">
                                	<title>Profesor añadido exitosamente</title>
                            	</head>
                            	<body>
                                	<div class="container">
                                    	<p>Profesor añadido exitosamente.</p>
                                	</div>
                            	</body>
                            	</html>';
                            	exit;
                        	} else {
                            	$mensaje = "Error al asignar el profesor al curso: " . $mysqli->error;
                        	}
                    	} else {
                        	$mensaje = "Error al crear el profesor: " . $mysqli->error;
                    	}
                	} else {
                    	$mensaje = "Error al subir la imagen del profesor.";
                	}
            	} else {
                	// Insertar los datos del nuevo profesor en la base de datos sin imagen
                	$sql = "INSERT INTO Profesores (DNI, Nombre, Apellidos, Titulacion, estatus) VALUES ('$dni', '$nombre', '$apellidos', '$titulacion', 'activo')";

                	if ($mysqli->query($sql) === TRUE) {
                    	// Actualizar la tabla de cursos con el DNI del profesor
                    	$sql = "UPDATE Cursos SET fk_profesor = '$dni' WHERE Codigo = '$codigo_curso'";
                    	if ($mysqli->query($sql) === TRUE) {
                        	// Redirige al usuario a la página de administración
                        	echo '<!DOCTYPE html>
                        	<html lang="es">
                        	<head>
                            	<meta charset="UTF-8">
                            	<meta http-equiv="refresh" content="3;url=administracion.php">
                            	<meta name="viewport" content="width=device-width, initial-scale=1.0">
                            	<link rel="stylesheet" href="administracion.scss">
                            	<title>Profesor añadido exitosamente</title>
                        	</head>
                        	<body>
                            	<div class="container">
                                	<p>Profesor añadido exitosamente.</p>
                            	</div>
                        	</body>
                        	</html>';
                        	exit;
                    	} else {
                        	$mensaje = "Error al asignar el profesor al curso: " . $mysqli->error;
                    	}
                	} else {
                    	$mensaje = "Error al crear el profesor: " . $mysqli->error;
                	}
            	}
        	}
    	}
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
	<title>Crear Profesor</title>
</head>
<body>
	<div class="container">
    	<h2>Crear Profesor</h2>
    	<div class="mensaje"><?php if (isset($mensaje)) echo $mensaje; ?></div>
    	<form action="crear_profesor.php" method="POST" enctype="multipart/form-data">
        	<label for="dni">DNI:</label>
        	<input type="text" id="dni" name="dni" maxlength="9" required>

        	<label for="nombre">Nombre:</label>
        	<input type="text" id="nombre" name="nombre" required>

        	<label for="apellidos">Apellidos:</label>
        	<input type="text" id="apellidos" name="apellidos" required>

        	<label for="titulacion">Titulación:</label>
        	<input type="text" id="titulacion" name="titulacion" required>

        	<label for="imagen">Imagen:</label>
        	<input type="file" id="imagen" name="imagen" accept="image/*">

        	<label for="codigo_curso">Código de Curso:</label>
        	<input type="text" id="codigo_curso" name="codigo_curso" required>

        	<input type="submit" value="Crear Profesor">
    	</form>
	</div>
    <div class="return-button">
        <a href="administracion.php">Volver atrás</a>
    </div>
</body>
</html>