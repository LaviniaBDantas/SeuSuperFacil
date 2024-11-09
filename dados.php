<?php
session_start(); 

include 'db.php'; 

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    // Redireciona para a página de login se não estiver logado
    $_SESSION['error'] = 'Você precisa estar logado para acessar esta página.';
    header("Location: login.html");
    exit;
}

// Se tiver, pega o CPF do usuário 
$user_cpf = $_SESSION['user_cpf'];

$sql = "SELECT nome, email, telefone,cpf FROM cliente WHERE cpf = :cpf";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cpf', $user_cpf, PDO::PARAM_STR);

$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

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

