<?php
$servidor = "dbXDebug";
$usuario = "tarefa";
$contrasinal = "Tarefa4.7";
$bd = "tarefa4.7";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $contrasinal);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro de conexion: " . $e->getMessage();
    die();
}