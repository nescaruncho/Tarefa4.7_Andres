<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el idioma de la cookie
$idioma = isset($_COOKIE['idioma']) ? $_COOKIE['idioma'] : 'es';
$saludo = ($idioma == 'es') ? 'Bienvenido' : 'Benvido';

try {
    // Obtener todos los productos
    $stmt = $conexion->query("SELECT * FROM produto");
    $productos = $stmt->fetchAll();

    // Obtener comentarios moderados
    $stmt = $conexion->prepare("SELECT idComentario, usuario, comentario FROM comentarios
                               WHERE moderado = 'si'");
    $stmt->execute();
    $comentarios = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Productos</title>
</head>

<body>
    <h1><?php echo $saludo . ", " . htmlspecialchars($_SESSION['usuario']); ?></h1>
    <a href="pecharSesion.php">Cerrar Sesión</a>

    <?php if ($_SESSION['rol'] == 'moderador'): ?>
        <a href="xestionaComentarios.php">Gestionar Comentarios</a>
    <?php endif; ?>

    <h2>Productos</h2>
    <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <h3><?php echo htmlspecialchars($producto['nome']); ?></h3>
            <p><?php echo htmlspecialchars($producto['descricion']); ?></p>
            <?php if (!empty($producto['imaxe'])): ?>
                <img src="<?php echo htmlspecialchars($producto['imaxe']); ?>" alt="Imagen del producto">
            <?php endif; ?>

            <h4>Comentarios:</h4>
            <?php
            foreach ($comentarios as $comentario) {
                if ($comentario['idProduto'] == $producto['idProduto']) {
                    echo "<p><strong>" . htmlspecialchars($comentario['nomeUsuario']) . ":</strong> ";
                    echo htmlspecialchars($comentario['comentario']) . "</p>";
                }
            }
            ?>

            <?php if ($_SESSION['rol'] == 'usuario'): ?>
                <form action="comenta.php" method="POST">
                    <input type="hidden" name="idProduto" value="<?php echo $producto['idProduto']; ?>">
                    <textarea name="comentario" required></textarea>
                    <input type="submit" value="Añadir Comentario">
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>

</html>