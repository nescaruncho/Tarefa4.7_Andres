<?php
session_start();
session_destroy();
setcookie(session_name(), '', time() - 42000, '/');
header("Location: login.php");
exit();
?>