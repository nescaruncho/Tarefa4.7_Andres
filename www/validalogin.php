<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = htmlspecialchars(trim($_POST['usuario']));
    $contrasinal = $_POST['contrasinal'];
    $idioma = $_POST['idioma'];
    
    try {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nomeUsuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($contrasinal, $user['contrasinal'])) {
            // Regenerar ID de sesión para prevenir fijación de sesiones
            session_regenerate_id(true);
            
            $_SESSION['usuario'] = $user['nomeUsuario'];
            $_SESSION['rol'] = $user['rol'];
            
            // Actualizar última fecha de conexión
            $stmt = $conexion->prepare("UPDATE usuarios SET ultimaConexion = NOW() WHERE nomeUsuario = ?");
            $stmt->execute([$usuario]);
            
            // Crear cookie de idioma (5 minutos)
            setcookie("idioma", $idioma, time() + 300);
            
            header("Location: mostra.php");
            exit();
        } else {
            header("Location: login.php?error=1");
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>