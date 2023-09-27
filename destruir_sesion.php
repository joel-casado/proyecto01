<?php
session_start();

// Destruye la sesión
session_destroy();

// Redirige al formulario de inicio de sesión
header("Location: login.html");
exit; // Exit para parar el script
?>
