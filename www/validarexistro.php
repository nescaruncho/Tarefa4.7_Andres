<?php
include('conexion.php');  // Aquí incluirías el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeUsuario = htmlspecialchars($_POST['nomeUsuario']);  // Sanitizamos los datos
    $nomeCompleto = htmlspecialchars($_POST['nomeCompleto']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);  // Guardar contraseña de forma segura

    // Validación (asegurarse de que no exista el usuario)
    $sql = "SELECT * FROM usuarios WHERE nomeUsuario = :nomeUsuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nomeUsuario', $nomeUsuario);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "El nombre de usuario ya está registrado.";
    } else {
        $rol = 'usuario';  // Rol por defecto
        $sql = "INSERT INTO usuarios (nomeUsuario, nomeCompleto, email, contraseña, rol) VALUES (:nomeUsuario, :nomeCompleto, :email, :contraseña, :rol)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nomeUsuario', $nomeUsuario);
        $stmt->bindParam(':nomeCompleto', $nomeCompleto);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contraseña', $contraseña);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        
        // Redirigir al login después de registrarse
        header("Location: login.php");
    }
}
?>
