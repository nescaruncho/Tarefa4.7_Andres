<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Ver qué datos estamos recibiendo
    error_log("Datos POST recibidos: " . print_r($_POST, true));
    error_log("Usuario en sesión: " . $_SESSION['usuario']);

    $idProduto = filter_var($_POST['idProduto'], FILTER_SANITIZE_NUMBER_INT);
    $comentario = htmlspecialchars(trim($_POST['comentario']), ENT_QUOTES, 'UTF-8');
    
    try {
        // 1. Obtener ID del usuario
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nomeUsuario = ?");
        $stmt->execute([$_SESSION['usuario']]);
        $userId = $stmt->fetchColumn();
        
        // Debugging: Verificar si obtuvimos el ID del usuario
        error_log("ID de usuario obtenido: " . ($userId !== false ? $userId : "no encontrado"));
        
        if ($userId === false) {
            error_log("No se encontró el ID del usuario: " . $_SESSION['usuario']);
            header("Location: mostra.php?error=usuario_no_encontrado");
            exit();
        }

        // 2. Verificar que el producto existe
        $stmt = $conexion->prepare("SELECT idProduto FROM produto WHERE idProduto = ?");
        $stmt->execute([$idProduto]);
        
        // Debugging: Verificar si el producto existe
        error_log("Producto encontrado: " . ($stmt->rowCount() > 0 ? "sí" : "no"));

        if ($stmt->rowCount() > 0) {
            // 3. Insertar el comentario
            $stmt = $conexion->prepare("INSERT INTO comentarios (usuario, idProduto, comentario, dataCreacion, moderado) 
                                      VALUES (?, ?, ?, NOW(), 'non')");
            
            // Debugging: Mostrar los valores que vamos a insertar
            error_log("Intentando insertar: usuario=$userId, idProduto=$idProduto, comentario=$comentario");
            
            $resultado = $stmt->execute([$userId, $idProduto, $comentario]);
            
            // Debugging: Verificar si la inserción fue exitosa
            if ($resultado) {
                error_log("Comentario insertado correctamente");
                header("Location: mostra.php?comentario=enviado");
                exit();
            } else {
                error_log("Error al insertar el comentario: " . print_r($stmt->errorInfo(), true));
                header("Location: mostra.php?error=error_insercion");
                exit();
            }
        } else {
            error_log("Producto no encontrado: " . $idProduto);
            header("Location: mostra.php?error=producto_invalido");
            exit();
        }
    } catch(PDOException $e) {
        error_log("Error en comenta.php: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        header("Location: mostra.php?error=error_sistema");
        exit();
    }
}

// Si alguien intenta acceder directamente, redirigir
header("Location: mostra.php");
exit();
?>