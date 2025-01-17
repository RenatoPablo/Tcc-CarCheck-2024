<?php
// controllers/getProdutosServicos.php
require '../../config.php';

$id_manutencao = $_GET['id'] ?? null;

if ($id_manutencao) {
    $stmt = $pdo->prepare("SELECT 
                                sp.id_servico_produto,
                                sp.nome_servico_produto,
                                sp.descricao,                                
                                sp.valor_servico_produto,
                                sp.quantidade,
                                sp.fk_id_tipo_servico,
                                sp.fk_id_marca_produto,
                                ims.quantidade,
                                ims.valor_uni
                            FROM 
                                itens_manutencoes_servicos ims
                            JOIN 
                                servicos_produtos sp ON ims.fk_id_servico_produto = sp.id_servico_produto
                            WHERE 
                                ims.fk_id_manutencao = :id_manutencao;
                            ");
    $stmt->execute([':id_manutencao' => $id_manutencao]);
    $produtosServicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($produtosServicos);
} else {
    echo json_encode(['error' => 'ID da manutenção não fornecido.']);
}
?>
