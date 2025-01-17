<?php

require '../config.php';



try {
    // Consulta para obter todas as formas de pagamento
    $sql = "SELECT * FROM formas_pagamento";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obter todos os resultados como array associativo
    $formas_pagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados como JSON
    echo json_encode($formas_pagamento);
} catch (PDOException $e) {
    // Retorna erro em formato JSON caso haja falha
    echo json_encode(['erro' => 'Erro ao buscar formas de pagamento: ' . $e->getMessage()]);
}
?>
