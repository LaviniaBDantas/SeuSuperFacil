<?php
session_start(); // Inicia a sessão
session_destroy(); // Destroi a sessão
header("Location: login.html"); // Redireciona para o login
exit;
?>
