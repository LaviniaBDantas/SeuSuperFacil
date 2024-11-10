<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Usuário não autenticado.";
    exit;
}

include 'db.php';

// Verifica se o ID da compra foi fornecido
if (!isset($_GET['compraId'])) {
    echo "ID da compra não especificado.";
    exit;
}

$compraId = $_GET['compraId'];

try {
    // Consulta para buscar os detalhes da compra
    $stmt = $pdo->prepare("SELECT c.id, c.total, c.data, c.endereco_entrega, c.cpf_cliente FROM compra c WHERE c.id = :id");
    $stmt->execute([':id' => $compraId]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$compra) {
        echo "Compra não encontrada.";
        exit;
    }

    // Consulta para buscar os produtos relacionados à compra
    $stmt = $pdo->prepare("SELECT p.descricao, ccp.cod_produto, ccp.quantidade
                           FROM compra_contem_produto ccp
                           JOIN produto p ON ccp.cod_produto = p.id
                           WHERE ccp.cod_compra = :cod_compra");
    $stmt->execute([':cod_compra' => $compraId]);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Erro ao recuperar detalhes da compra: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Compra</title>
</head>

<body>
    <h1>Detalhes da Compra</h1>

    <h2>Informações da Compra</h2>
    <p><strong>ID da Compra:</strong> <?php echo htmlspecialchars($compra['id']); ?></p>
    <p><strong>Total:</strong> R$ <?php echo number_format($compra['total'], 2, ',', '.'); ?></p>
    <p><strong>Data:</strong> <?php echo htmlspecialchars($compra['data']); ?></p>
    <p><strong>Endereço de Entrega:</strong> <?php echo htmlspecialchars($compra['endereco_entrega']); ?></p>
    <p><strong>CPF do Cliente:</strong> <?php echo htmlspecialchars($compra['cpf_cliente']); ?></p>

    <h2>Produtos Comprados</h2>
    <?php if (count($produtos) > 0): ?>
        <ul>
            <?php foreach ($produtos as $produto): ?>
                <li>
                    <strong>Produto:</strong> <?php echo htmlspecialchars($produto['descricao']); ?><br>
                    <strong>Quantidade:</strong> <?php echo htmlspecialchars($produto['quantidade']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum produto encontrado para esta compra.</p>
    <?php endif; ?>
</body>

</html>