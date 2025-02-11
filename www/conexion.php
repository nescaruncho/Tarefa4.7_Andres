<?php
// Parámetros de conexión
$host = 'localhost';  // Dirección del servidor de base de datos (puede ser localhost o una IP)
$db = 'tarefa4.7';  // Nombre de la base de datos
$user = 'tarefa';  // Usuario de la base de datos
$pass = 'Tarefa4.7';  // Contraseña del usuario

// Establecer la conexión utilizando PDO
try {
    // Creamos una instancia PDO para conectar con la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Configuramos PDO para lanzar excepciones en caso de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opcional: Configuramos el modo de los resultados
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si ocurre un error, lo mostramos
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
