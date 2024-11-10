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
    // Consulta para buscar os detalhes da compra e o nome do cliente
    $stmt = $pdo->prepare("SELECT c.id, c.total, c.data, c.endereco_entrega, c.cpf_cliente, cl.nome 
    FROM compra c
    JOIN cliente cl ON c.cpf_cliente = cl.cpf
    WHERE c.id = :id");
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
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Executa a consulta e armazena o resultado em $produtos

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
    <style>
        body {
            background-color: rgb(219, 213, 197);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .caixa {
            width: 700px;
            background-color: whitesmoke;
            margin-top: 20px;
            box-sizing: border-box;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 10px;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
        }

        p,
        ul {
            margin: 15px 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        strong {
            display: inline-block;
            width: 150px;
        }

        button {
            padding: 10px 20px;
            font-size: 14px;
            display: block;
            width: 30%;
            margin: 10px auto;
            border-radius: 20px;
            border: 1px solid #ccc;
            color: white;
            background-color: red;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <div class="caixa">
        <h1>Detalhes da Compra</h1>

        <h2>Informações da Compra</h2>
        <p><strong>ID da Compra:</strong> <?php echo htmlspecialchars($compra['id']); ?></p>
        <p><strong>Nome do Cliente:</strong> <?php echo htmlspecialchars($compra['nome']); ?></p>
        <p><strong>CPF do Cliente:</strong> <?php echo htmlspecialchars($compra['cpf_cliente']); ?></p>
        <p><strong>Total:</strong> R$ <?php echo number_format($compra['total'], 2, ',', '.'); ?></p>
        <p><strong>Data:</strong> <?php echo htmlspecialchars($compra['data']); ?></p>
        <p><strong>Endereço de Entrega:</strong> <?php echo htmlspecialchars($compra['endereco_entrega']); ?></p>

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
        <button onclick="window.location.href='home.html'">Ir para a Home</button>
    </div>
</body>

</html>