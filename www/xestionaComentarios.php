<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario es moderador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'moderador') {
    header("Location: mostra.php");
    exit();
}

// Procesar acciones de moderación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idComentario = filter_var($_POST['idComentario'], FILTER_SANITIZE_NUMBER_INT);
    $accion = $_POST['accion'];
    
    try {
        if ($accion == 'aprobar') {
            $stmt = $conexion->prepare("UPDATE comentarios SET moderado = 'si', dataModeración = NOW() WHERE id = ?");
        } else if ($accion == 'eliminar') {
            $stmt = $conexion->prepare("DELETE FROM comentarios WHERE id = ?");
        }
        $stmt->execute([$idComentario]);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener comentarios sin moderar
try {
    $stmt = $conexion->prepare("SELECT c.*, u.nomeUsuario, p.nome as nomeProduto 
                               FROM comentarios c 
                               JOIN usuarios u ON c.usuario = u.nomeUsuario 
                               JOIN produto p ON c.idProduto = p.idProduto 
                               WHERE c.moderado = 'non'");
    $stmt->execute();
    $comentarios = $stmt->fetchAll();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Comentarios</title>
</head>
<body>
    <h1>Gestión de Comentarios</h1>
    <a href="pechaSesion.php">Cerrar Sesión</a>
    <a href="mostra.php">Volver a Productos</a>
    
    <?php if (empty($comentarios)): ?>
        <p>No hay comentarios pendientes de moderación.</p>
    <?php else: ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($comentario['nomeUsuario']); ?></p>
                <p><strong>Producto:</strong> <?php echo htmlspecialchars($comentario['nomeProduto']); ?></p>
                <p><strong>Comentario:</strong> <?php echo htmlspecialchars($comentario['Comentario']); ?></p>
                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($comentario['dataCreación']); ?></p>
                
                <form method="POST">
                    <input type="hidden" name="idComentario" value="<?php echo $comentario['id']; ?>">
                    <button type="submit" name="accion" value="aprobar">Aprobar</button>
                    <button type="submit" name="accion" value="eliminar">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>