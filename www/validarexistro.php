<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeUsuario = htmlspecialchars(trim($_POST['nomeUsuario']));
    $nomeCompleto = htmlspecialchars(trim($_POST['nomeCompleto']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contrasinal = password_hash($_POST['contrasinal'], PASSWORD_DEFAULT);
    
    try {
        // Verificar si el usuario ya existe
        $stmt = $conexion->prepare("SELECT nomeUsuario FROM usuarios WHERE nomeUsuario = ?");
        $stmt->execute([$nomeUsuario]);
        
        if ($stmt->rowCount() > 0) {
            header("Location: rexistro.html?error=usuario_existe");
            exit();
        }
        
        // Insertar nuevo usuario
        $stmt = $conexion->prepare("INSERT INTO usuarios (nomeUsuario, nomeCompleto, contrasinal, email, rol) VALUES (?, ?, ?, ?, 'usuario')");
        $stmt->execute([$nomeUsuario, $nomeCompleto, $contrasinal, $email]);
        
        header("Location: login.php?registro=exitoso");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>