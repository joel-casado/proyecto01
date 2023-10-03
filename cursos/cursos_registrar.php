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

    //Calcular el id de la nueva inscripción
    $newID = mysqli_num_rows($result);
    
    //Conseguir un array de las inscripciones (chatgpt)
    if ($result->num_rows > 0) {
        while($array = $result->fetch_assoc()){
            if ($array['fk_estudiante'] == $fk_student){
                if($array['fk_curso'] == $fk_curso){
                    // Redirigir al alumno
                    echo "Registro ya existente.";
                    header("Location: ../student/home_estudiante.php");
                    exit();
                }
                else {
                    //Insertar la nueva inscripción en la base de datos
                    $newID++;
                    $newsql = "INSERT INTO inscripciones (ID_inscripcion, fk_estudiante, fk_curso) VALUES ('$newID', '$fk_student', '$fk_curso')";
            
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
            }
        }
        //print_r($tabla);
    } else {
        echo "No se encontraron registros";
    }


?>