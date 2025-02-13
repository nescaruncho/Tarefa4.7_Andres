<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado y regenerar ID de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
session_regenerate_id(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitización más estricta de las entradas
    $idProduto = filter_var($_POST['idProduto'], FILTER_SANITIZE_NUMBER_INT);
    $comentario = htmlspecialchars(trim($_POST['comentario']), ENT_QUOTES, 'UTF-8');
    $usuario = htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');
    
    try {
        // Verificar que el producto existe antes de insertar el comentario
        $stmt = $conexion->prepare("SELECT idProduto FROM produto WHERE idProduto = ?");
        $stmt->execute([$idProduto]);
        
        if ($stmt->rowCount() > 0) {
            $stmt = $conexion->prepare("INSERT INTO comentarios (usuario, idProduto, Comentario, dataCreación, moderado) 
                                      VALUES (?, ?, ?, NOW(), 'non')");
            $stmt->execute([$usuario, $idProduto, $comentario]);
            
            header("Location: mostra.php?comentario=enviado");
            exit();
        } else {
            header("Location: mostra.php?error=producto_invalido");
            exit();
        }
    } catch(PDOException $e) {
        error_log("Error en comenta.php: " . $e->getMessage());
        header("Location: mostra.php?error=error_sistema");
        exit();
    }
}

// Si alguien intenta acceder directamente, redirigir
header("Location: mostra.php");
exit();
?>