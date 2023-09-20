<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_admin.scss">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="login_admin.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <input type="submit" value="Iniciar Sesión">
        </form>
        <?php
        // Mostrar mensaje de contraseña incorrecta si es necesario
        if (isset($_GET["error"]) && $_GET["error"] === "contrasena") {
            echo '<p style="color: red;">Contraseña incorrecta.</p>';
        }
        ?>
    </div>
</body>
</html>
