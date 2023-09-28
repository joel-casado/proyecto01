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
    	<link rel="stylesheet" href="../administracion.scss">
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/crear_profesor.scss">
	<title>Editar Curso</title>
</head>
<body>
	<h1>Editar Curso</h1>
	<?php
	// Conexión a la base de datos
	$conexion = new mysqli("localhost", "admin1", "admin", "learning_academy");

	// Verificar la conexión
	if ($conexion->connect_error) {
    	die("Error de conexión: " . $conexion->connect_error);
	}

	if (isset($_GET["codigo"])) {
		$codigo = $_GET["codigo"];
	
		// Verificar si se ha enviado el formulario de edición
		if (isset($_POST["guardar"])) {
			// Obtener los nuevos valores del formulario
			$nombre = $_POST["nombre"];
			$descripcion = $_POST["descripcion"];
			$horas = $_POST["horas"];
			$fecha_inicio = $_POST["fecha_inicio"];
			$fecha_fin = $_POST["fecha_fin"];
			$estatus = $_POST["estatus"]; // Agrega esta línea para obtener el nuevo valor del estatus
	
			// Actualizar los datos en la base de datos, incluyendo el estatus
			$update_query = "UPDATE Cursos SET Nombre = '$nombre', Descripcion = '$descripcion', Horas = $horas, Fecha_inicio = '$fecha_inicio', Fecha_fin = '$fecha_fin', estatus = '$estatus' WHERE Codigo = '$codigo'";
			if ($conexion->query($update_query) === TRUE) {
				echo "Los datos del curso se actualizaron correctamente.";
			} else {
				echo "Error al actualizar los datos: " . $conexion->error;
			}
		}
	
		// Consulta para obtener los detalles del curso
		$query = "SELECT * FROM Cursos WHERE Codigo = '$codigo'";
		$result = $conexion->query($query);
	
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			?>
			<form method="post">
				<label for="nombre">Nombre del Curso:</label>
				<input type="text" name="nombre" value="<?php echo $row["Nombre"]; ?>" required><br>
				<label for="descripcion">Descripción:</label>
				<textarea name="descripcion" rows="4" required><?php echo $row["Descripcion"]; ?></textarea><br>
				<label for="horas">Horas:</label>
				<input type="number" name="horas" value="<?php echo $row["Horas"]; ?>" required><br>
				<label for="fecha_inicio">Fecha de Inicio:</label>
				<input type="date" name="fecha_inicio" value="<?php echo $row["Fecha_inicio"]; ?>" required><br>
				<label for="fecha_fin">Fecha de Fin:</label>
				<input type="date" name="fecha_fin" value="<?php echo $row["Fecha_fin"]; ?>" required><br>
				<label for="estatus">Estatus:</label>
				<select name="estatus">
					<option value="activo" <?php if ($row["estatus"] == "activo") echo "selected"; ?>>Activo</option>
					<option value="inactivo" <?php if ($row["estatus"] == "inactivo") echo "selected"; ?>>Inactivo</option>
				</select><br>
				<input type="submit" name="guardar" value="Guardar Cambios">
			</form>
			<?php
		} else {
			echo "Curso no encontrado.";
		}
	} else {
		echo "Código de curso no especificado.";
	}
	// Cerrar la conexión a la base de datos
	$conexion->close();
	?>
    <div class="return-button">
    	<a href="modificar_curso.php">Volver atrás</a>
	</div>

</body>
</html>