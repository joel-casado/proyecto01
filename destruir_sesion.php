<?php
// Inicia la sesión (debe estar en la parte superior del archivo)
session_start();

// Destruye la sesión
session_destroy();

// Redirige al formulario de inicio de sesión
header("Location: login_admin_datos.php");
exit; // Asegura que el script se detenga después de la redirección
?>
