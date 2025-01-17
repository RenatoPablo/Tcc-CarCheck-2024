<?php
// controllers/addProdutoServico.php
require '../../config.php';

$id_manutencao = $_POST['id_manutencao'] ?? null;
$nome = $_POST['nome'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$quantidade = $_POST['quantidade'] ?? null;
$valor_unitario = $_POST['valor_unitario'] ?? null;

if ($id_manutencao && $nome && $quantidade && $valor_unitario) {
    $stmt = $pdo->prepare("INSERT INTO servicos_produtos (id_manutencao, nome, descricao, quantidade, valor_unitario) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_manutencao, $nome, $descricao, $quantidade, $valor_unitario]);

    echo json_encode(['success' => 'Produto/Serviço adicionado com sucesso.']);
} else {
    echo json_encode(['error' => 'Dados insuficientes para adicionar o produto/serviço.']);
}
?>
