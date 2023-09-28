<?php
session_start();

// Verificar si se ha iniciado sesión como estudiante
if (!isset($_SESSION["tipo_usuario"]) || $_SESSION["tipo_usuario"] !== "estudiante") {
    header("Location: login.php"); // Redirigir al inicio de sesión si no se ha iniciado sesión como estudiante
    exit();
}

// Conectar a la base de datos (debes proporcionar la información de conexión)
$servername = "localhost";
$username = "admin1";
$password = "admin";
$dbname = "learning_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el DNI del estudiante actual (puedes usar la sesión para esto)
$DNI_estudiante = $_SESSION["DNI_estudiante"];

// Consulta para obtener los cursos en los que el estudiante está inscrito
$sql = "SELECT Cursos.Codigo, Cursos.Nombre, Inscripciones.Nota
        FROM Cursos
        INNER JOIN Inscripciones ON Cursos.Codigo = Inscripciones.fk_curso
        WHERE Inscripciones.fk_estudiante = '$DNI_estudiante'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Estudiante</title>
</head>
<body>
    <h2>Bienvenido, Estudiante</h2>
    
    <h3>Tus Cursos:</h3>
    <table border="1">
        <tr>
            <th>Código</th>
            <th>Nombre del Curso</th>
            <th>Nota</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Codigo"] . "</td>";
                echo "<td>" . $row["Nombre"] . "</td>";
                echo "<td>" . $row["Nota"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No estás inscrito en ningún curso.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="../cursos/cursos.php">Inscribete a un curso ahora!</a>
    <br>
    <a href="../destruir_sesion.php">Cerrar Sesión</a>
</body>
</html>
