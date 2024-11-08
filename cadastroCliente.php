<?php
include 'db.php'; // Inclua o arquivo que contém a conexão com o banco de dados

// Permissões para evitar problemas de CORS em desenvolvimento local (opcional)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];
    $senha2 = $_POST['senha2'];

    // Validar os dados recebidos
    if (empty($nome) || empty($cpf) || empty($email) || empty($telefone) || empty($senha) || empty($senha2)) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    // Verificar se as senhas coincidem
    if ($senha !== $senha2) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Hash da senha para maior segurança (opcional, mas recomendável)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Preparar e executar a query para inserir o usuário no banco usando PDO
    $sql = "INSERT INTO cliente (nome, cpf, email, telefone, senha) VALUES (:nome, :cpf, :email, :telefone, :senha)";
    $stmt = $pdo->prepare($sql);

    // Executar e verificar se a inserção foi bem-sucedida
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
