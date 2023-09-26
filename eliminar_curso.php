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


//Verifica si se ha pedido la modificacion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["mark_inactive_course"])) {
    $codigo_curso = $_POST["mark_inactive_course"];

    // Actualiza el campo "estatus" a "inactivo" en la base de datos
    $sql_update = "UPDATE Cursos SET estatus = 'inactivo' WHERE Codigo = '$codigo_curso'";
    if ($mysqli->query($sql_update) === TRUE) {
        $update_message = "Curso marcado como inactivo exitosamente.";
    } else {
        $update_message = "Error al marcar el curso como inactivo: " . $mysqli->error;
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
                	echo '<td>
							<form method="POST">
								<input type="hidden" name="mark_inactive_course" value="' . $row["Codigo"] . '">
								<button type="submit" onclick="return confirm(\'¿Estás seguro de marcar este curso como inactivo?\')">Marcar como inactivo</button>
							</form>
						</td>';
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