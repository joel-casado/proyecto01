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
    $sql = "SELECT * FROM inscripciones";
    $result = mysqli_query($conn, $sql);
    
    if ($result['fk_estudiante']==$fk_student && $result['fk_curso']==$fk_curso){
        echo "Ja et trobes registrat a aquest curs.";
    }

    //Obtener el id de la nueva inscripcion
    $fila = COUNT($result);
    echo 'Número de total de registros: ' . $fila['total'];
    $ID = $fila['total'] +1;


    //Insertar la nueva inscripción en la base de datos
    $newsql = "INSERT INTO inscripciones (ID_inscripcion, fk_estudiante, fk_curso) VALUES ('$ID', '$fk_student', '$fk_curso')";

    if ($conn->query($newsql) === TRUE) {
        // Redirigir al alumno luego de inscribir-se
        echo "Registro exitoso.";
        header("Location: ../student/home_estudiante.php");
        exit();

    } else {
        echo "Error en el registro: " . $conn->error;
    }
    
?>