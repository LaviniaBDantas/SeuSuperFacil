<?php
    $host = 'localhost'; // Substitua pelo seu host, se necessário
    $db = 'seusuperfacil'; // Nome do banco de dados
    $user = 'root'; // Usuário do banco de dados
    $pass = ''; // Senha do banco de dados
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
?>