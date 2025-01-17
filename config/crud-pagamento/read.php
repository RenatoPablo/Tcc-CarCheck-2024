<?php
require '../config.php';

header('Content-Type: application/json');

if (isset($_GET['id_manutencao'])) {
    $idManutencao = intval($_GET['id_manutencao']);
    //var_dump($idManutencao);

    try {
        $stmt = $pdo->prepare("SELECT id_pagamento, codigo, valor_parcela, data_parcela, fk_id_forma_pagamento, num_parcela, situacao
                               FROM pagamentos 
                               WHERE fk_id_manutencao = :id_manutencao");
        $stmt->execute([':id_manutencao' => $idManutencao]);

        $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($pagamentos) {
            echo json_encode(['success' => true, 'pagamentos' => $pagamentos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum pagamento encontrado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de manutenção não fornecido.']);
}
?>
