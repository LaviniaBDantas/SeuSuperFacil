<?php
session_start(); 

include 'db.php'; // Inclui a conexão com o banco de dados

// Pega o CPF do usuário da sessão
$user_cpf = $_SESSION['user_id'];

// Prepara a consulta para buscar os dados do cliente pelo CPF
$sql = "SELECT nome, email, telefone,cpf FROM cliente WHERE cpf = :cpf";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cpf', $user_cpf, PDO::PARAM_STR);

// Executa a consulta
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se encontrou o usuário
if (!$cliente) {
    echo "Usuário não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Dados</title>
    <link rel="stylesheet" href="styleDados.css" type="text/css">
</head>
<body>
<div class="caixa-dados">
    <h1>Meus Dados</h1>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome']); ?></p>
    <p><strong>CPF:</strong> <?php echo htmlspecialchars($cliente['cpf']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente['email']); ?></p>
    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($cliente['telefone']); ?></p>

    <button onclick="window.location.href='home.html'">Voltar para Home</button>
</div>

</body>
</html>

