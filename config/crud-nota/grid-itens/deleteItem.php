<?php
require '../../config.php';

header('Content-Type: application/json');

// Ative o modo de erro para capturar quaisquer problemas no banco de dados
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['itemId']) && isset($_POST['idManutencao'])) {
    $itemId = (int)$_POST['itemId'];
    $idManutencao = (int)$_POST['idManutencao'];

    try {
        // Prepare a consulta de exclusão
        $stmt = $pdo->prepare("DELETE FROM itens_manutencoes_servicos WHERE fk_id_servico_produto = :itemId AND fk_id_manutencao = :idManutencao");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':idManutencao', $idManutencao, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Responde com sucesso em JSON
            echo json_encode(['success' => true, 'message' => 'Item removido com sucesso.']);
        } else {
            // Responde com erro em JSON
            echo json_encode(['success' => false, 'message' => 'Falha ao remover item.']);
        }
    } catch (PDOException $e) {
        // Captura e responde com erro de exceção
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    // Caso falte itemId ou idManutencao
    echo json_encode(['success' => false, 'message' => 'ID do item ou ID da manutenção não especificado.']);
}
