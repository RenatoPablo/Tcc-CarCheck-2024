<?php 

session_start();

require '../config.php';

try {
    $sql = "SELECT 
                s.id_servico_produto,
                s.nome_servico_produto,
                s.descricao,
                s.valor_servico_produto,
                s.quantidade,
                m.id_marca_produto,
                m.nome_marca_produto
            FROM
                servicos_produtos s
            LEFT JOIN
                marcas_servicos_produtos m ON m.id_marca_produto = s.fk_id_marca_produto
            WHERE 
                s.fk_id_tipo_servico = 2";
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


?>