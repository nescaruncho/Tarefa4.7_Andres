<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $idProduto = filter_var($_POST['idProduto'], FILTER_SANITIZE_NUMBER_INT);
    $comentario = htmlspecialchars(trim($_POST['comentario']));

    try {
        
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE nomeUsuario = ?");
        $stmt->execute([$_SESSION['usuario']]);
        $idUsuario = $stmt->fetchColumn();

        if (!$idUsuario) {
            header("Location: mostra.php?error=usuario_no_encontrado");
            exit();
        }

        $stmt = $conexion->prepare("SELECT idProduto FROM produto WHERE idProduto = ?");
        $stmt->execute([$idProduto]);

        if ($stmt->rowCount() === 0) {
            header("Location: mostra.php?error=producto_no_encontrado");
            exit();
        }

        $stmt = $conexion->prepare("INSERT INTO comentarios (usuario, idProduto, comentario, dataCreacion, moderado)
                                    VALUES (?, ?, ?, NOW(), 'non')");
        $resultado = $stmt->execute([$idUsuario, $idProduto, $comentario]);

        if (!$resultado) {
            header("Location: mostra.php?error=error_insercion_comentario");
            exit();
        } else {
            header("Location: mostra.php?comentario=enviado");
            exit();
        }


    } catch (PDOException $e) {
        header("Location: mostra.php?error=error_sistema");
    }
} else {
    header("Location: login.php");
    exit();
}