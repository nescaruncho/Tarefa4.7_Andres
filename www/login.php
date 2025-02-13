<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="validalogin.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required><br>
        
        <label for="contrasinal">Contraseña:</label>
        <input type="password" name="contrasinal" required><br>
        
        <label for="idioma">Idioma:</label>
        <select name="idioma">
            <option value="es">Español</option>
            <option value="gl">Galego</option>
        </select><br>
        
        <input type="submit" value="Iniciar Sesión">
    </form>
    <a href="rexistro.html">Registrarse</a>
</body>
</html>