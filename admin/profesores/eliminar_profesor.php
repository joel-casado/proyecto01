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
    	<meta http-equiv="refresh" content="4;url=../login_admin_datos.php">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link rel="stylesheet" href="../css/administracion.scss">
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

// Verifica si se ha enviado una solicitud para cambiar el estatus del profesor
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["professor_id"]) && isset($_POST["change_status"])) {
	$professor_id = $_POST["professor_id"];
	$new_status = $_POST["change_status"];

	// Actualiza el estatus del profesor en la base de datos
	$sql_update = "UPDATE Profesores SET estatus = '$new_status' WHERE DNI = '$professor_id'";
	if ($mysqli->query($sql_update) === TRUE) {
    	$update_message = "Estatus del profesor actualizado exitosamente.";
	} else {
    	$update_message = "Error al actualizar el estatus del profesor: " . $mysqli->error;
	}
}

// Consulta profesores con estatus "activo"
$sql_select = "SELECT * FROM Profesores WHERE estatus = 'activo'";
$result = $mysqli->query($sql_select);

// Cierra la conexión a la base de datos
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/eliminar_curso.scss">
	<title>Eliminar Profesores</title>
</head>
<body>
	<div class="container">
    	<h2>Eliminar Profesores</h2>
    	<div class="mensaje"><?php if (isset($update_message)) echo $update_message; ?></div>
    	<table>
        	<thead>
            	<tr>
                	<th>DNI</th>
                	<th>Nombre</th>
                	<th>Apellidos</th>
                	<th>Titulación</th>
                	<th>Estatus</th>
                	<th>Acciones</th>
            	</tr>
        	</thead>
        	<tbody>
            	<?php
            	while ($row = $result->fetch_assoc()) {
                	echo "<tr>";
                	echo "<td>" . $row["DNI"] . "</td>";
                	echo "<td>" . $row["Nombre"] . "</td>";
                	echo "<td>" . $row["Apellidos"] . "</td>";
                	echo "<td>" . $row["Titulacion"] . "</td>";
                	echo "<td>" . $row["estatus"] . "</td>";
                	echo '<td><form method="POST">
                        	<input type="hidden" name="professor_id" value="' . $row["DNI"] . '">
                        	<input type="hidden" name="change_status" value="inactivo">
                        	<button type="submit" onclick="return confirm(\'¿Estás seguro de cambiar el estatus de este profesor a inactivo?\')">Eliminar</button>
                        	</form></td>';
                	echo "</tr>";
            	}
            	?>
        	</tbody>
    	</table>
	</div>
	<div class="return-button">
    	<a href="../administracion.php">Volver atrás</a>
	</div>
</body>
</html>