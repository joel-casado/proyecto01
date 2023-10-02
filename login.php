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

    // Verificar si las credenciales corresponden a un estudiante con contraseña encriptada
    $sql = "SELECT DNI, Contrasena FROM Estudiantes WHERE DNI = '$usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["Contrasena"];
        
        // Verificar la contraseña proporcionada con la contraseña encriptada en la base de datos
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión de estudiante
            $_SESSION["DNI_estudiante"] = $row["DNI"];
            $_SESSION["tipo_usuario"] = "estudiante";
            header("Location: student/home_estudiante.php");
            exit();
        }
    }

    // Verificar si las credenciales corresponden a un profesor con contraseña encriptada
    $sql = "SELECT DNI, Contrasena FROM Profesores WHERE DNI = '$usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["Contrasena"];
        
        // Verificar la contraseña proporcionada con la contraseña encriptada en la base de datos
        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión de profesor
            $_SESSION["DNI_profesor"] = $row["DNI"];
            $_SESSION["tipo_usuario"] = "profesor";
            header("Location: home_profesor.php");
            exit();
        }
    }

    // Si no se encontraron coincidencias en ninguna de las dos tablas, redirige con un mensaje de error
    header("Location: home_estudiante.php");
    exit();
}
?>
