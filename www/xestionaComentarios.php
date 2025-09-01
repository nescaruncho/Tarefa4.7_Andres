<?php

session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] !== 'moderador') {
    header("Location: mostra.php");
    exit();
}

try {
    $stmt = $conexion->prepare("SELECT c.*, u.nomeUsuario, p.nome as nomeProduto
                                FROM comentarios c
                                JOIN usuarios u ON c.usuario = u.id
                                JOIN produto p ON c.idProduto = p.idProduto
                                WHERE c.moderado = 'non'");
    $stmt->execute();
    $comentarios = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $idComentario = filter_var($_POST['idComentario'], FILTER_SANITIZE_NUMBER_INT);
    $accion = $_POST['accion'];

    try {
        if ($accion == 'aprobar') {
            $stmt = $conexion->prepare("UPDATE comentarios SET moderado = 'si', dataModeracion = NOW()
                                        WHERE idComentario = ?");
            $stmt->execute([$idComentario]);
        } elseif ($accion == 'eliminar') {
            $stmt = $conexion->prepare("DELETE FROM comentarios WHERE idComentario = ?");
            $stmt->execute([$idComentario]);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Xestionar comentarios</title>
</head>
<body>
    <h1>Xestionar comentarios</h1>
    <a href="pecharSesion.php">Pechar sesión</a>
    <a href="mostra.php">Volver a produtos</a>

    <?php if(empty($comentarios)): ?>
        <p>Non hai comentarios pendentes de moderar</p>
    <?php else: ?>
        <?php foreach($comentarios as $comentario): ?>
            <div class="comentario">
                <p><strong>Usuario: </strong> <?php echo htmlspecialchars($comentario['nomeUsuario']); ?> </p>
                <p><strong>Produto: </strong> <?php echo htmlspecialchars($comentario['nomeProduto']); ?> </p>
                <p><strong>Comentario: </strong> <?php echo htmlspecialchars($comentario['comentario']); ?> </p>
                <p><strong>Data: </strong> <?php echo htmlspecialchars($comentario['dataCreacion']); ?> </p>

                <form method="post">
                    <input type="hidden" name="idComentario" value="<?php echo $comentario['idComentario']; ?>">
                    <button type="submit" name="accion" value="aprobar">Aprobar</button>
                    <button type="submit" name="accion" value="eliminar">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>