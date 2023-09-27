<?php
// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar los datos del formulario
    $dni = $_POST["dni"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $edad = $_POST["edad"];
    $contrasena = $_POST["contrasena"];

    // Validar los datos (puedes agregar más validaciones según tus necesidades)
    if (empty($dni) || empty($nombre) || empty($apellidos) || empty($edad) || empty($contrasena)) {
        echo "Por favor, complete todos los campos.";
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

    // Verificar si el DNI ya está registrado
    $sql = "SELECT DNI FROM Estudiantes WHERE DNI = '$dni'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "El DNI ya está registrado. Por favor, inicia sesión.";
        header("Location: registro_estudiante.html");
        exit();
    }

    // Insertar el nuevo estudiante en la base de datos
    $sql = "INSERT INTO Estudiantes (DNI, Nombre, Apellidos, Edad, Contrasena) VALUES ('$dni', '$nombre', '$apellidos', $edad, '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. Puedes iniciar sesión ahora.";
    } else {
        echo "Error en el registro: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Redirigir si se intenta acceder al archivo directamente sin enviar el formulario
    header("Location: .registro_estudiante.html");
    exit();
}
?>