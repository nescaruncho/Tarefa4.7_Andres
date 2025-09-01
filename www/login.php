<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Login</title>
</head>
<body>
    <form action="validaLogin.php" method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required>

        <label for="contrasinal">Contrasinal:</label>
        <input type="password" name="contrasinal" id="contrasinal">

        <label for="idioma">Idioma:</label>
        <select name="idioma" id="idioma">
            <option value="es">Español</option>
            <option value="gl">Galego</option>
        </select>

        <input type="submit" value="Iniciar sesión">
        <a href="rexistro.html">Crear unha conta</a>
    </form>
</body>
</html>