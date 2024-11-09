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
        echo "<script>alert('Todos os campos são obrigatórios.'); window.history.back();</script>";
        exit;
    }

    if ($senha !== $senha2) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
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
        echo "<script>alert('Cadastro realizado com sucesso.'); window.location.href='home.html';</script>";
        exit;
    } catch (PDOException $e) {
        // Verifica se o erro é de duplicidade
        if ($e->getCode() == 23000) { 
            echo "<script>alert('Usuário já cadastrado.'); window.history.back();</script>";
        } else {
            echo "<script>alert('Erro ao realizar o cadastro. Tente novamente mais tarde.'); window.history.back();</script>";
        }
    }
} else {
    echo "<script>alert('Método POST não foi usado.'); window.history.back();</script>";
}
?>
