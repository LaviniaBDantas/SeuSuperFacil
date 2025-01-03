<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Usuário não autenticado.";
    exit;
}

include 'db.php'; // Inclui a conexão com o banco de dados

$user_cpf = $_SESSION['user_id'];

// Consulta para buscar os dados do cliente
$sql = "SELECT nome, telefone, cpf FROM cliente WHERE cpf = :cpf";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cpf', $user_cpf, PDO::PARAM_STR);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo "Usuário não encontrado.";
    exit;
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Carrinho</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleCarrinho.css" type="text/css">
    <script src="carrinho.js"></script>
</head>

<body class="bg-body-tertiary">
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
                    <a class="nav-link" href="http://localhost/SeuSuperFacil/produtos.php">Produtos</a>
                </li>
            </ul>
            <form class="d-flex ms-auto" method="GET" action="buscarProduto.php">
                    <input class="form-control me-2" type="search" name="query" placeholder="Buscar" aria-label="Buscar" required>
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


    <div class="container-fluid">
        <main>
            <div class="py-5 text-center">
                <h2>Finalize sua compra!</h2>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-success">Seu carrinho</span>
                        <span class="badge bg-success rounded-pill" id="total-itens">0 itens</span>
                    </h4>
                    <ul class="list-group mb-3" id="lista-carrinho">
                        <!-- Produtos do carrinho serão inseridos aqui via JS -->
                    </ul>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <strong id="total-preco">R$ 0,00</strong>
                    </li>
                    <form id="form-finalizar-compra" action="finalizarCompra.php" method="POST">
                        <input type="hidden" name="total" id="input-total"> <!-- Total do carrinho -->
                        <input type="hidden" name="itens" id="input-itens"> <!-- Produtos no carrinho -->

                        <!-- Campos de endereço para serem enviados no formulário -->
                        <input type="hidden" name="rua" id="input-rua">
                        <input type="hidden" name="numero" id="input-numero">
                        <input type="hidden" name="cidade" id="input-cidade">
                        <input type="hidden" name="estado" id="input-estado">
                        <input type="hidden" name="cep" id="input-cep">

                        <script>
                            function carregarCamposEndereco() {
                                document.getElementById('input-rua').value = document.getElementById('address').value;
                                document.getElementById('input-numero').value = document.getElementById('num').value;
                                document.getElementById('input-cidade').value = document.getElementById('city').value;
                                document.getElementById('input-estado').value = document.getElementById('state').value;
                                document.getElementById('input-cep').value = document.getElementById('zip').value;
                            }

                            document.getElementById('form-finalizar-compra').addEventListener('submit', function (event) {
                                carregarCamposEndereco();

                                // Carregar os itens do carrinho com suas quantidades para envio
                                let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
                                let produtoIds = carrinho.map(item => ({
                                    id: item.id,
                                    descricao: item.descricao,
                                    preco: item.preco,
                                    quantidade: item.quantidade
                                }));
                            });
                        </script>

                        <button type="submit" class="btn btn-success w-100 mt-3">Finalizar Compra</button>
                    </form>


                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="container">
                        <form class="needs-validation" novalidate>
                            <div class="row g-3">
                                <!-- Preenchendo os campos com valores do banco de dados -->
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="firstName"
                                        value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf"
                                        value="<?php echo htmlspecialchars($cliente['cpf']); ?>" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="tel" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="tel"
                                        value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Endereço -->
                            <div class="row g-3">
                                <h4 class="mb-3">Endereço</h4>
                                <div class="col-12">
                                    <label for="address" class="form-label">Rua</label>
                                    <input type="text" class="form-control" id="address" placeholder="Digite sua rua"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label for="num" class="form-label">Número</label>
                                    <input type="number" class="form-control" id="num" placeholder="Número" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="country" class="form-label">País</label>
                                    <select class="form-select" id="country" required>
                                        <option value="">Escolha uma opção...</option>
                                        <option>Brasil</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">Estado</label>
                                    <select class="form-select" id="state" required>
                                        <option value="">Escolha uma opção...</option>
                                        <option>Goiás</option>
                                        <option>Minas Gerais</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="city" class="form-label">Cidade</label>
                                    <select class="form-select" id="city" required>
                                        <option value="">Escolha uma opção...</option>
                                        <option>Goiânia</option>
                                        <option>Aparecida de Goiânia</option>
                                        <option>Monte Carmelo</option>
                                        <option>Patos de Minas</option>
                                        <option>Uberlândia</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="zip" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="zip" placeholder="Ex: 12345-000"
                                        required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Forma de Pagamento -->
                            <h4 class="mb-3">Forma de pagamento</h4>
                            <div class="my-3">
                                <div class="form-check">
                                    <input id="credit" name="paymentMethod" type="radio" class="form-check-input"
                                        checked required>
                                    <label class="form-check-label" for="credit">Cartão de crédito</label>
                                </div>
                                <div class="form-check">
                                    <input id="debit" name="paymentMethod" type="radio" class="form-check-input"
                                        required>
                                    <label class="form-check-label" for="debit">Cartão de débito</label>
                                </div>
                                <div class="form-check">
                                    <input id="pix" name="paymentMethod" type="radio" class="form-check-input" required>
                                    <label class="form-c    heck-label" for="pix">Pix</label>
                                </div>
                            </div>


                            <hr class="my-4">
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <footer class="my-5 pt-5 text-body-secondary text-center text-small">
            <p class="mb-1">&copy; 2017–2024 Company Name</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacy</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">Support</a></li>
            </ul>
        </footer>
    </div>

</body>

</html>