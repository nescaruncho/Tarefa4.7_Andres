<?php
session_start();

require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'es';
$saludo = ($idioma === 'es') ? 'Bienvenido, ' . htmlspecialchars($_SESSION['usuario']) : 'Benvido, ' . htmlspecialchars($_SESSION['usuario']);

try {
    $stmt = $conexion->query("SELECT * FROM produto");
    $produtos = $stmt->fetchAll();

    $stmtComentarios = $conexion->prepare("SELECT c.idComentario, c.usuario, c.comentario, c.dataCreacion, u.nomeUsuario FROM comentarios c JOIN usuarios u ON u.id = c.usuario WHERE moderado = 'si' AND c.idProduto = ?");

} catch (PDOException $e) {
    echo "Erro conectando a base de datos: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Mostra</title>
</head>

<body>
    <h1><?php echo $saludo ?></h1>
    <a href="pechaSesion.php">Pechar sesión</a>

    <?php if($_SESSION['rol'] === 'moderador'): ?>
        <a href="xestionaComentarios.php">Ir a xestionar comentarios</a>
    <?php endif; ?>

    <h2>Produtos</h2>
    <?php foreach ($produtos as $produto): ?>
        <div class="producto">
            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
            <p><?php echo htmlspecialchars($produto['descricion']); ?></p>
            <?php if (!empty($produto['imaxe'])): ?>
                <img src="<?php echo htmlspecialchars($produto['imaxe']); ?>"
                    alt="Imaxe de <?php echo htmlspecialchars($produto['nome']); ?>">
            <?php endif; ?>
            <h4>Comentarios</h4>
            <?php
            $stmtComentarios->execute([$produto['idProduto']]);
            $comentarios = $stmtComentarios->fetchAll();
            ?>

            <?php if (count($comentarios) === 0): ?>
                <p class="muted">Ainda non hai comentarios</p>
            <?php else: ?>
                <ul class="comentarios">
                    <?php foreach ($comentarios as $comentario): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($comentario['nomeUsuario']); ?></strong><br>
                            <?php echo nl2br(htmlspecialchars($comentario['comentario'])); ?><br>
                            <small><?php echo htmlspecialchars($comentario['dataCreacion']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['rol'] === 'usuario'): ?>

                <form action="comenta.php" method="post">
                    <label for="comentario">Introduce o teu comentario:</label>
                    <input type="hidden" name="idProduto" value="<?php echo $produto['idProduto']; ?>">
                    <input type="text" name="comentario" id="comentario" required>
                    <input type="submit" name="enviarComentario" value="Enviar">
                </form>

            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>

</html>