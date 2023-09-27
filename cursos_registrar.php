<?php
    session_start();
    //Codigo del curso al que matricular-se
    $fk_curso = $_GET['Codigo'];
    //echo "$fk_curso";
    
    //Obtener el dni del estudiante
    $fk_student = $_SESSION["DNI_estudiante"];
    //echo "$fk_student";
    
    // Conectar a la base de datos (debes proporcionar la información de conexión)
    $servername = "localhost";
    $username = "admin1";
    $password = "admin";
    $dbname = "learning_academy";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    //Obtener el id de la nueva inscripcion
    $sql = "SELECT COUNT(*) total FROM inscripciones";
    $result = mysqli_query($conn, $sql);
    $fila = mysqli_fetch_assoc($result);
    echo 'Número de total de registros: ' . $fila['total'];
    $ID = $fila['total'] +1;


    //Insertar la nueva inscripción en la base de datos
    $newsql = "INSERT INTO inscripciones (ID_inscripcion, fk_estudiante, fk_curso) VALUES ('$ID', '$fk_student', '$fk_curso')";

    if ($conn->query($newsql) === TRUE) {
        // Redirigir al alumno luego de inscribir-se
        header("Location: home_estudiante.php");
        exit();
        echo "Registro exitoso.";

    } else {
        echo "Error en el registro: " . $conn->error;
    }
    
?>