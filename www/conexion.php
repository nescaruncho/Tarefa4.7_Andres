<?php
// Parámetros de conexión
$host = 'localhost';
$db = 'tarefa4.7';
$user = 'tarefa';
$pass = 'Tarefa4.7';

// Establecer la conexión utilizando PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
