<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="4;url=login_admin_datos.php">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../administracion.scss">
        <title>No tienes permiso</title>
    </head>
    <body>
        <div class="container">
            <p>No tienes permiso para acceder a esta página.</p>
        </div>
    </body>
    </html>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/eliminar_curso.scss">
    <title>Lista de Cursos</title>
</head>
<body>
    <h1>Lista de Cursos</h1>
    <?php
    $conexion = new mysqli("localhost", "admin1", "admin", "learning_academy");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $query = "SELECT Cursos.Codigo, Cursos.Nombre, Cursos.Descripcion, Cursos.Horas, Cursos.Fecha_inicio, Cursos.Fecha_fin, Profesores.Nombre AS NombreProfesor, Profesores.Apellidos AS ApellidosProfesor, Cursos.estatus
              FROM Cursos
              LEFT JOIN Profesores ON Cursos.fk_profesor = Profesores.DNI";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Código</th><th>Nombre</th><th>Descripción</th><th>Horas</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Profesor</th><th>Estatus</th><th>Acciones</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Codigo"] . "</td>";
            echo "<td>" . $row["Nombre"] . "</td>";
            echo "<td>" . $row["Descripcion"] . "</td>";
            echo "<td>" . $row["Horas"] . "</td>";
            echo "<td>" . $row["Fecha_inicio"] . "</td>";
            echo "<td>" . $row["Fecha_fin"] . "</td>";

            $profesor_nombre = "Sin Profesor";
            if (!empty($row["NombreProfesor"]) && !empty($row["ApellidosProfesor"])) {
                $profesor_nombre = $row["NombreProfesor"] . " " . $row["ApellidosProfesor"];
            }

            echo "<td>" . $profesor_nombre . "</td>";

            echo "<td>";
            echo "<select name='estatus' id='estatus_" . $row["Codigo"] . "' onchange='actualizarEstatus(\"" . $row["Codigo"] . "\")'>";
            echo "<option value='activo'" . ($row["estatus"] === 'activo' ? ' selected' : '') . ">Activo</option>";
            echo "<option value='inactivo'" . ($row["estatus"] === 'inactivo' ? ' selected' : '') . ">Inactivo</option>";
            echo "</select>";
            echo "</td>";

            echo "<td><a href='editar_curso.php?codigo=" . $row["Codigo"] . "'>Editar</a></td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay cursos disponibles.";
    }

    $conexion->close();
    ?>

    <div class="return-button">
        <a href="../administracion.php">Volver atrás</a>
    </div>

    <script>
    function actualizarEstatus(codigo) {
        const selectElement = document.getElementById('estatus_' + codigo);
        const nuevoEstatus = selectElement.value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../funciones/actualizar_estatus.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('Estatus actualizado en la base de datos.');
            }
        };
        xhr.send('codigo=' + codigo + '&estatus=' + nuevoEstatus);
    }
    </script>
</body>
</html>