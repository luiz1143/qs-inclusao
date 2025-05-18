<?php
session_start();

// Encerrar a sessão
$_SESSION = array();
session_destroy();

// Redirecionar para a página de login
header("location: login.php");
exit;
?>
