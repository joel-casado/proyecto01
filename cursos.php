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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ListadoCursos</title>
    <link rel="stylesheet" href="css/cursos.scss">
</head>
<body>
    <div class="container">
        <div id="top">
            <h1>Bienvenidos a la biblioteca de idiomas</h1>
            <h2>Selecciona el curso al que te quieras matricular</h2>
        </div>
        <div class="shelf">
            <?php
                $sql="SELECT * FROM cursos";
                $result = $conn->query($sql);
            
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        if ($row['estatus']== 'activo'){
                            echo "<div class='course'>".$row['Nombre']."</div>";

                            
                        }
                    }
                }
                else{
                    echo "No hi ha cursos disponibles";
                }
                // Cerrar la conexión a la base de datos
                $conn->close();
            ?>
        </div>
        <a href="home_estudiante.php">Volver</a>
    </div>
</body>
</html>