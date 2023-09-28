<?php
session_start(); // Iniciar la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    
    // Conectar a la base de datos (debes proporcionar la información de conexión)
    $servername = "localhost";
    $username = "admin1";
    $password = "admin";
    $dbname = "learning_academy";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Verificar si las credenciales corresponden a un estudiante
    $sql = "SELECT DNI FROM Estudiantes WHERE DNI = '$usuario' AND Contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Obtener el DNI del estudiante y guardarlo en la sesión
        $row = $result->fetch_assoc();
        $_SESSION["DNI_estudiante"] = $row["DNI"];
        
        // Iniciar sesión de estudiante
        $_SESSION["tipo_usuario"] = "estudiante";
        header("Location: student/home_estudiante.php");
        exit();
    }

    // Verificar si las credenciales corresponden a un profesor
    $sql = "SELECT DNI FROM Profesores WHERE DNI = '$usuario' AND Contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Iniciar sesión de profesor
        $_SESSION["tipo_usuario"] = "profesor";
        header("Location: home_profesor.php");
        exit();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}

// Si no se encontraron coincidencias, redirige de nuevo a la página de inicio de sesión con un mensaje de error
header("Location: login.html?error=1");
exit();
?>
