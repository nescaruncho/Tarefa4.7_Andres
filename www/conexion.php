<?php
$servidor = "dbXdebug";
$usuario = "tarefa";
$contrasinal = "Tarefa4.7";
$bd = "tarefa4.7";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $usuario, $contrasinal);
    // Configurar el modo de error PDO para que lance excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Configurar el modo de fetch por defecto a FETCH_ASSOC
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}
?>