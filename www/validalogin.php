<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $contrasinal = htmlspecialchars($_POST['contrasinal']);
    $idioma = $_POST['idioma'] ?? 'es';

    try {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($contrasinal, $user['contrasinal'])) {
            session_regenerate_id(true);

            $_SESSION['usuario'] = $user['nomeUsuario'];
            $_SESSION['rol'] = $user['rol'];

            $stmt = $conexion->prepare("UPDATE usuarios SET ultimaConexion = NOW() WHERE email = ?");
            $stmt->execute([$email]);

            setcookie("idioma", $idioma, time() + 300);

            header("Location: mostra.php");

            exit();
        } else {
            header("Location: login.php?error=login-incorrecto");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: login.php");
}
