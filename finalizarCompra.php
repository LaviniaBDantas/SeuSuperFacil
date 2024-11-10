<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Usuário não autenticado.";
    exit;
}

include 'db.php';

// Dados recebidos do formulário
$total = $_POST['total'] ?? 0;
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
if (empty($itens)) {
    echo "Nenhum item encontrado no carrinho.";
    exit;
}

try {
    $pdo->beginTransaction();

    // Criar um novo ID de compra manualmente para ser usado como chave
    $stmt = $pdo->query("SELECT IFNULL(MAX(cod_compra), 0) + 1 AS next_id FROM compra_contem_produto");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $compraId = $result['next_id'];

    // Inserir itens na tabela 'compra_contem_produto'
    $stmt = $pdo->prepare("INSERT INTO compra_contem_produto (cod_compra, cod_produto, quantidade) VALUES (:cod_compra, :cod_produto, :quantidade)");
foreach ($itens as $item) {
    $stmt->execute([
        ':cod_compra' => $compraId,
        ':cod_produto' => $item['id'],
        ':quantidade' => $item['quantidade'] // Certifique-se de que esta linha está presente
    ]);
}


    // Inserir na tabela 'compra' com o ID de compra gerado
    $stmt = $pdo->prepare("INSERT INTO compra (id, total, data, endereco_entrega, cpf_cliente) VALUES (:id, :total, NOW(), :endereco, :cpf_cliente)");
    $stmt->execute([
        ':id' => $compraId,
        ':total' => $total,
        ':endereco' => $enderecoCompleto,
        ':cpf_cliente' => $cpf_cliente
    ]);

    $pdo->commit();
    
    // Redirecionar para a página de detalhes após o commit
    header("Location: detalhesCompra.php?compraId=" . $compraId);
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Falha ao finalizar compra: " . $e->getMessage();
}
?>
