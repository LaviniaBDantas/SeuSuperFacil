<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Usuário não autenticado.";
    exit;
}

include 'db.php';

// Dados recebidos do formulário
$total = $_POST['total'];
$cpf_cliente = $_SESSION['user_id'];

// Dados do endereço
$rua = $_POST['rua'] ?? 'Rua não especificada';
$numero = $_POST['numero'] ?? 'S/N';
$cidade = $_POST['cidade'] ?? 'Cidade não especificada';
$estado = $_POST['estado'] ?? 'Estado não especificado';
$cep = $_POST['cep'] ?? 'CEP não especificado';

// Concatenar endereço em uma string
$enderecoCompleto = "$rua, Número: $numero, $cidade - $estado, CEP: $cep";

// Decodifica os itens do carrinho recebidos via JSON
$itens = json_decode($_POST['itens'], true);

try {
    $pdo->beginTransaction();

    // Inserir itens na tabela 'compra_contem_produto'
    $compraId = null; // Atribuir um ID temporário
    $stmt = $pdo->prepare("INSERT INTO compra_contem_produto (cod_compra, cod_produto) VALUES (:cod_compra, :cod_produto)");
    foreach ($itens as $item) {
        $stmt->execute([
            ':cod_compra' => $compraId, // Este ID será atualizado quando 'compra' for inserido
            ':cod_produto' => $item['id']
        ]);
    }

    // Inserir na tabela 'compra'
    $stmt = $pdo->prepare("INSERT INTO compra (total, data, endereco_entrega, cpf_cliente, email) VALUES (:total, NOW(), :endereco, :cpf_cliente, :email)");
    $stmt->execute([
        ':total' => $total,
        ':endereco' => $enderecoCompleto,
        ':cpf_cliente' => $cpf_cliente,
        ':email' => $_POST['email']
    ]);

    // Atualizar o ID de compra com o autoincrement gerado
    $compraId = $pdo->lastInsertId();

    // Atualizar `compra_contem_produto` com o ID correto
    foreach ($itens as $item) {
        $stmt = $pdo->prepare("UPDATE compra_contem_produto SET cod_compra = :cod_compra WHERE cod_produto = :cod_produto");
        $stmt->execute([
            ':cod_compra' => $compraId,
            ':cod_produto' => $item['id']
        ]);
    }

    $pdo->commit();
    echo "Compra finalizada com sucesso!";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Falha ao finalizar compra: " . $e->getMessage();
}
?>
