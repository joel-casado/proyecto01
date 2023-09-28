<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "admin1", "admin", "learning_academy");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST["codigo"];
    $nuevoEstatus = $_POST["estatus"];

    // Actualizar el estado en la base de datos
    $query = "UPDATE Cursos SET estatus = ? WHERE Codigo = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ss", $nuevoEstatus, $codigo);
    
    if ($stmt->execute()) {
        echo "Estatus actualizado correctamente.";
    } else {
        echo "Error al actualizar el estatus.";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conexion->close();
}
?>
