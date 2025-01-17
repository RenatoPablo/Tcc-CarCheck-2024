<?php
require '../config.php';

try {
    // Prepara a consulta
    $sql = "SELECT
                id_servico_produto,
                nome_servico_produto,
                descricao, 
                valor_servico_produto    
                FROM 
                    servicos_produtos 
                WHERE 
                    fk_id_tipo_servico = 1";
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
