<?php
// Inicia la sesión
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
    	<link rel="stylesheet" href="administracion.scss">
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
    <link rel="stylesheet" href="crear_profesor.scss">
    <title>Editar Profesor</title>
</head>
<body>
    <h1>Editar Profesor</h1>
    <?php
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "admin1", "admin", "learning_academy");

    // Verificar la conexión
    if ($conexion->connect_error) {
   	 die("Error de conexión: " . $conexion->connect_error);
    }

    if (isset($_GET["dni"])) {
   	 $dni = $_GET["dni"];

   	 // Obtener la imagen actual del profesor y el estatus actual
   	 $query = "SELECT Fotografia, estatus FROM Profesores WHERE DNI = '$dni'";
   	 $result = $conexion->query($query);

   	 if ($result->num_rows == 1) {
   		 $row = $result->fetch_assoc();
   		 $imagen_actual = $row["Fotografia"];
   		 $estatus_actual = $row["estatus"]; // Obtener el estatus actual
   	 } else {
   		 echo "Profesor no encontrado.";
   		 exit;
   	 }

   	 // Verificar si se ha enviado el formulario de edición
   	 if (isset($_POST["guardar"])) {
   		 // Obtener los nuevos valores del formulario
   		 $nombre = $_POST["nombre"];
   		 $apellidos = $_POST["apellidos"];
   		 $titulacion = $_POST["titulacion"];
   		 $estatus = $_POST["estatus"]; // Obtener el nuevo valor del estatus

   		 // Verificar si se ha subido una nueva imagen
   		 if ($_FILES["fotografia"]["error"] === 0) {
       		 // Se ha subido una nueva imagen, procesarla
       		 $imagen_tmp = $_FILES["fotografia"]["tmp_name"];
       		 $imagen_nombre = $_FILES["fotografia"]["name"];
       		 $extension = pathinfo($imagen_nombre, PATHINFO_EXTENSION);
       		 $nueva_imagen = "./". $dni . "." . $extension; // Ruta donde guardar la nueva imagen

       		 // Mover la imagen a la ubicación deseada
       		 if (move_uploaded_file($imagen_tmp, $nueva_imagen)) {
           		 // La imagen se ha subido correctamente
           		 $imagen_actual = $nueva_imagen;
       		 }
   		 }

   		 // Actualizar los datos en la base de datos, incluyendo el estatus
   		 $update_query = "UPDATE Profesores SET Nombre = '$nombre', Apellidos = '$apellidos', Titulacion = '$titulacion', estatus = '$estatus', Fotografia = '$imagen_actual' WHERE DNI = '$dni'";
   		 if ($conexion->query($update_query) === TRUE) {
       		 echo "Los datos del profesor se actualizaron correctamente.";
   		 } else {
       		 echo "Error al actualizar los datos: " . $conexion->error;
   		 }
   	 }

   	 // Consulta para obtener los detalles del profesor
   	 $query = "SELECT * FROM Profesores WHERE DNI = '$dni'";
   	 $result = $conexion->query($query);

   	 if ($result->num_rows == 1) {
   		 $row = $result->fetch_assoc();
   		 ?>
   		 <form method="post" enctype="multipart/form-data">
       		 <label for="nombre">Nombre:</label>
       		 <input type="text" name="nombre" value="<?php echo $row["Nombre"]; ?>" required><br>
       		 <label for="apellidos">Apellidos:</label>
       		 <input type="text" name="apellidos" value="<?php echo $row["Apellidos"]; ?>" required><br>
       		 <label for="titulacion">Titulación:</label>
       		 <input type="text" name="titulacion" value="<?php echo $row["Titulacion"]; ?>" required><br>
       		 <label for="estatus">Estatus:</label>
       		 <input type="text" name="estatus" value="<?php echo $estatus_actual; ?>" required><br> <!-- Mostrar el estatus actual -->
       		 <label for="fotografia">Fotografía:</label>
       		 <input type="file" name="fotografia"><br>
       		 <input type="submit" name="guardar" value="Guardar Cambios">
   		 </form>
   		 <?php
   	 } else {
   		 echo "Profesor no encontrado.";
   	 }
    } else {
   	 echo "DNI del profesor no especificado.";
    }

    $conexion->close();
    ?>
    <div class="return-button">
   	 <a href="modificar_profesor.php">Volver atrás</a>
    </div>
</body>
</html>



