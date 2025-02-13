<?php
session_start();
session_destroy(); // Eliminamos todas las variables de sesión
setcookie(session_name(), '', time() - 42000, '/'); // Eliminamos la cookie de sesión
header("Location: login.php");
exit();
?>
