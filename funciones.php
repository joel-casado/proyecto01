<?php
    function connect(){
        // Conectar a la base de datos (debes proporcionar la información de conexión)
        $servername = "localhost";
        $username = "admin1";
        $password = "admin";
        $dbname = "learning_academy";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        else{
            return $conn;
        }
    }
?>