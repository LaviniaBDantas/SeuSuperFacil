<?php
include 'db.php'; 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];
    $senha2 = $_POST['senha2'];

    if (empty($nome) || empty($cpf) || empty($email) || empty($telefone) || empty($senha) || empty($senha2)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    if ($senha !== $senha2) {
        echo "As senhas não coincidem.";
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO cliente (nome, cpf, email, telefone, senha) VALUES (:nome, :cpf, :email, :telefone, :senha)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':nome' => $nome,
            ':cpf' => $cpf,
            ':email' => $email,
            ':telefone' => $telefone,
            ':senha' => $senhaHash,
        ]);
        echo "Cadastro realizado com sucesso.";
        header("Location: home.html"); 
        exit;
    } catch (PDOException $e) {
        echo "Erro ao realizar o cadastro: " . $e->getMessage();
    }
} else {
    echo "Método POST não foi usado.";
}
?>
