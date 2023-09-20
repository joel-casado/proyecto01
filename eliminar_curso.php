<?php
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
	exit;
}

// Establece la conexión a la base de datos
$mysqli = new mysqli("localhost", "admin1", "admin", "learning_academy");

// Verifica errores en la conexión
if ($mysqli->connect_error) {
	die("Error en la conexión: " . $mysqli->connect_error);
}

// Verifica si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_course"])) {
	$codigo_curso = $_POST["delete_course"];

	// Elimina el curso de la base de datos
	$sql_delete = "DELETE FROM Cursos WHERE Codigo = '$codigo_curso'";
	if ($mysqli->query($sql_delete) === TRUE) {
    	$delete_message = "Curso eliminado exitosamente.";
	} else {
    	$delete_message = "Error al eliminar el curso: " . $mysqli->error;
	}
}

// Consulta todos los cursos de la base de datos
$sql_select = "SELECT * FROM Cursos";
$result = $mysqli->query($sql_select);

// Cierra la conexión a la base de datos
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="eliminar_curso.scss">
	<title>Borrar Cursos</title>
</head>
<body>
	<div class="container">
    	<h2>Borrar Cursos</h2>
    	<div class="mensaje"><?php if (isset($delete_message)) echo $delete_message; ?></div>
    	<table>
        	<thead>
            	<tr>
                	<th>Código</th>
                	<th>Nombre</th>
                	<th>Descripción</th>
                	<th>Horas</th>
                	<th>Fecha de Inicio</th>
                	<th>Fecha de Finalización</th>
                	<th>Acciones</th>
            	</tr>
        	</thead>
        	<tbody>
            	<?php
            	while ($row = $result->fetch_assoc()) {
                	echo "<tr>";
                	echo "<td>" . $row["Codigo"] . "</td>";
                	echo "<td>" . $row["Nombre"] . "</td>";
                	echo "<td>" . $row["Descripcion"] . "</td>";
                	echo "<td>" . $row["Horas"] . "</td>";
                	echo "<td>" . $row["Fecha_inicio"] . "</td>";
                	echo "<td>" . $row["Fecha_fin"] . "</td>";
                	echo '<td><form method="POST"><input type="hidden" name="delete_course" value="' . $row["Codigo"] . '"><button type="submit" onclick="return confirm(\'¿Estás seguro de eliminar este curso?\')">Borrar</button></form></td>';
                	echo "</tr>";
            	}
            	?>
        	</tbody>
    	</table>
	</div>
	<div class="return-button">
    	<a href="administracion.php">Volver atrás</a>
	</div>
</body>
</html>