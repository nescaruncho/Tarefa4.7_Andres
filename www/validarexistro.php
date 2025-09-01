<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeUsuario = htmlspecialchars(trim($_POST ['nomeUsuario']));
    $nomeCompleto = htmlspecialchars(trim($_POST['nomeCompleto']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contrasinal = password_hash($_POST['contrasinal'], PASSWORD_DEFAULT);

    try {
        $stmt = $conexion->prepare("SELECT email FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            header("Location: rexistro.html?error=usuario_existe");
            exit();
        }

        $stmt = $conexion->prepare("INSERT INTO usuarios (nomeUsuario, nomeCompleto, email, contrasinal, rol) VALUES (?, ?, ?, ?, 'usuario')");
        $stmt->execute([$nomeUsuario, $nomeCompleto, $email, $contrasinal]);

        header("Location: login.php?rexistro=exitoso");
        exit();

    } catch (PDOException $e) {
        echo "Erro no rexistro:" . $e->getMessage();
    }
}