<?php
// Define o cabeçalho JSON
header('Content-Type: application/json');

// Certifique-se de que a sessão está ativa antes de qualquer validação de autenticação
session_start();



// Confirma que o método HTTP é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}

require '../config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idPagamento = $_POST['id_pagamento'] ?? null;

        if ($idPagamento) {
            // Atualize o pagamento no banco de dados
            $sql = "UPDATE pagamentos SET situacao = 1 WHERE id_pagamento = :id_pagamento";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id_pagamento' => $idPagamento]);

            // Retorne sucesso como JSON
            echo json_encode(['success' => true, 'message' => 'Pagamento realizado com sucesso.']);
        } else {
            // ID não fornecido
            echo json_encode(['success' => false, 'message' => 'ID do pagamento não fornecido.']);
        }
    } else {
        // Método HTTP inválido
        echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    }
} catch (Exception $e) {
    // Retorne o erro como JSON
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
    // Finaliza o script para evitar saída extra
    exit;
}
?>