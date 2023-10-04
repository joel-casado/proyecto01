<?php
    session_start();
    
    // Conectar a la base de datos (debes proporcionar la información de conexión)
    $servername = "localhost";
    $username = "admin1";
    $password = "admin";
    $dbname = "learning_academy";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    //Codigo del curso al que matricular-se
    $fk_curso = $_GET['Codigo'];

    //Obtener el dni del estudiante
    $fk_student = $_SESSION["DNI_estudiante"];

    //Operaciones de la tabla inscripciones
    $sql = "SELECT * FROM inscripciones WHERE fk_estudiante = '$fk_student' AND fk_curso = '$fk_curso'";
    $result = mysqli_query($conn, $sql);

    if($result->num_rows > 0){
        // Redirigir al alumno
        echo "Registro ya existente.";
        header("Location: ../student/home_estudiante.php");
        exit();
    }
    else{
        //Insertar la nueva inscripción en la base de datos
        $newID++;
        print_r($newID);
        $newsql = "INSERT INTO inscripciones (fk_estudiante, fk_curso) VALUES ('$fk_student', '$fk_curso')";

        if ($conn->query($newsql) === TRUE) {
            // Redirigir al alumno luego de inscribir-se
            echo "Registro exitoso.";
            header("Location: ../student/home_estudiante.php");
            exit();
        } 
        else {
            echo "Error en el registro: " . $conn->error;
        }
    }

?>