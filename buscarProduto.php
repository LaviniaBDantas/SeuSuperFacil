<?php
session_start();
include 'db.php'; // Conexão com o banco de dados

// Verifica se há uma query de busca
if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    echo "Nenhum termo de busca foi fornecido.";
    exit;
}

$busca = trim($_GET['query']);

try {
    // Consulta para buscar produtos com nomes que correspondem ao termo de busca
    $stmt = $pdo->prepare("SELECT * FROM produto WHERE descricao LIKE :busca");
    $stmt->execute([':busca' => '%' . $busca . '%']);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() === 0) {
        echo "Nenhum resultado encontrado.";
    }

} catch (Exception $e) {
    echo "Erro ao buscar produtos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function adicionarAoCarrinho(produtoId, descricao, preco) {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

            let produtoExistente = carrinho.find(item => item.id === produtoId);
            if (produtoExistente) {
                produtoExistente.quantidade += 1;
            } else {
                carrinho.push({
                    id: produtoId,
                    descricao: descricao,
                    preco: preco,
                    quantidade: 1
                });
            }

            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            alert('Produto adicionado ao carrinho!');
        }
    </script>
     <style>
        body {
            background-color: rgb(219, 213, 197);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .caixa {
            width: 700px;
            height: auto;
            background-color: whitesmoke;
            margin-top: 20px;
            box-sizing: border-box;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Sombra cinza escura */
            position: relative; /* Permite usar top, left */
            border-radius: 10px; /* Arredonda as bordas da div */
            padding: 20px;
            margin: 20px auto;
        }

    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Resultados da Busca</h1>
        <p>Termo pesquisado: <strong><?php echo htmlspecialchars($busca); ?></strong></p>

        <?php if (count($resultados) > 0): ?>
            <div class="row">
                <?php foreach ($resultados as $produto): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="imagens/<?php echo htmlspecialchars($produto['imagem']); ?>" class="card-img-top" alt="Imagem do Produto">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($produto['descricao']); ?></h5>
                                <p class="card-text">Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                                <button class="btn btn-success mt-2" onclick="adicionarAoCarrinho(<?php echo $produto['id']; ?>, '<?php echo htmlspecialchars($produto['descricao']); ?>', <?php echo $produto['preco']; ?>)">Adicionar ao Carrinho</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhum produto encontrado para o termo <strong><?php echo htmlspecialchars($busca); ?></strong>.</p>
        <?php endif; ?>
        <div class="mt-4">
        <button onclick="window.history.back()" class="btn btn-danger" style="padding: 12px 20px; margin-top: -20px;">Voltar</button>
        </div>
    </div>
</body>
</html>
