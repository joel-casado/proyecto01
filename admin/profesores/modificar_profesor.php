<?php
// Inicia la sesión
session_start();

// Verifica si la sesión del administrador no está activa
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    // Si no está activa, muestra un mensaje y redirige después de 4 segundos
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
    exit; // Asegura que el script se detenga después de mostrar el mensaje
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "admin1", "admin", "learning_academy");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener la lista de profesores incluyendo estatus
$query = "SELECT DNI, Nombre, Apellidos, Titulacion, Fotografia, estatus FROM Profesores";
$result = $conexion->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/eliminar_curso.scss">
    <title>Lista de Profesores</title>
</head>
<body>
    <h1>Lista de Profesores</h1>
    <table border="1">
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Titulación</th>
            <th>Fotografía</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["DNI"] . "</td>";
            echo "<td>" . $row["Nombre"] . "</td>";
            echo "<td>" . $row["Apellidos"] . "</td>";
            echo "<td>" . $row["Titulacion"] . "</td>";
            // Mostrar la imagen usando una etiqueta <img> con la ruta de la imagen
            echo "<td><img src='" . $row["Fotografia"] . "' alt='Imagen del profesor' width='100'></td>";
            echo "<td>";
            echo "<select name='estatus' id='estatus_" . $row["DNI"] . "' onchange='actualizarEstatus(\"" . $row["DNI"] . "\")'>";
            echo "<option value='activo'" . ($row["estatus"] === 'activo' ? ' selected' : '') . ">Activo</option>";
            echo "<option value='inactivo'" . ($row["estatus"] === 'inactivo' ? ' selected' : '') . ">Inactivo</option>";
            echo "</select>";
            echo "</td>";
            echo "<td><a href='editar_profesor.php?dni=" . $row["DNI"] . "&estatus=" . $row["estatus"] . "'>Editar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div class="return-button">
        <a href="../administracion.php">Volver atrás</a>
    </div>
    <script>
    function actualizarEstatus(DNI) {
        const selectElement = document.getElementById('estatus_' + DNI);
        const nuevoEstatus = selectElement.value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../funciones/actualizar_estatus_profesor.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('Estatus actualizado en la base de datos.');
            }
        };
        xhr.send('DNI=' + DNI + '&estatus=' + nuevoEstatus);
    }
    </script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
