<?php
include 'db.php';

$sql = "SELECT * FROM produto"; // Altere para o nome correto da tabela se necessário
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="styleProdutos.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="carrinho.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="imagens/Logo.jpg" width="90" height="90" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="home.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ofertas</a>
                </li>
            </ul>
            <form class="d-flex ms-auto">
                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar">
                <button class="btn btn-outline-success" type="submit">
                    <i class="fas fa-search"></i> <!-- Ícone de lupa -->
                </button>
            </form>
            <a class="navbar-brand" href="pagLogin.php">
                <img src="imagens/userLogin.png" width="30" height="30" alt="Login Icon">
            </a>
            <a class="navbar-brand" href="carrinho.php">
    <div style="position: relative;">
        <img src="imagens/carrinho.png" width="30" height="30" alt="Carrinho de compras">
        <!-- Badge para mostrar o número de itens do carrinho -->
        <span id="cart-count" style="
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            display: none;">0</span>
    </div>
</a>
        </div>
    </nav>

    <div class="text-center mt-4">
       
    </div>

    <div class="container mt-auto" style="max-width:fit-content;">
        <div class="row">
        <?php foreach ($produtos as $produto): ?>
    <div class="col-auto">
        <div class="card" style="width: 100%; max-width: 20rem;">
        <img src="imagens/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do Produto" class="card-img-top" style="width: 150px; height: auto; margin: 0 auto; display: block;">
        <div class="card-body">
            <h7 class="card-title">Cod <?= htmlspecialchars($produto['id']) ?></h7>
            <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>
            <p class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            <p class="stock">Estoque: <?= htmlspecialchars($produto['qtd_estoque']) ?> unidades</p>

            <button class="btn btn-dark btn-block" onclick="adicionarAoCarrinho(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['descricao']) ?>', <?= $produto['preco'] ?>)">Adicionar ao carrinho</button>

        </div>
        </div>
    </div>
<?php endforeach; ?>
        </div>
    </div>

    <footer class="bg-danger text-light w-100 mt-auto py-4">
        <div class="container">
            <div class="row">
                <!-- Coluna de Contatos -->
                <div class="col-md-6">
                    <h2>Contatos</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="https://wa.me/3491564999" class="text-light">
                                <i class="fab fa-whatsapp fa-lg"></i> (34) 99860-7000
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="text-light">
                                <i class="fab fa-instagram fa-lg"></i> Fique por dentro das novidades
                            </a>
                        </li>
                    </ul>
                </div>
    
                <!-- Coluna de Endereços Atendidos -->
                <div class="col-md-6">
                    <h2>Endereços Atendidos</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <p class="text-light">Rua Exemplo, 123 - Bairro Centro</p>
                        </li>
                        <li class="nav-item">
                            <p class="text-light">Avenida Principal, 456 - Bairro Jardim</p>
                        </li>
                        <li class="nav-item">
                            <p class="text-light">Rua Secundária, 789 - Bairro São José</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


</body>
</html>
