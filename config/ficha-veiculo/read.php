<?php 

// Configura o cabeçalho para retorno em JSON
header('Content-Type: application/json');

require '../config.php';

// Verifica se o método HTTP é GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recebe o parâmetro de busca
    $placa = $_GET['placa'] ?? ''; // Usa o operador null coalescing para evitar erros caso o parâmetro não seja enviado

    if (empty($placa)) {
        // Retorna uma resposta de erro se a placa não for enviada
        echo json_encode(['success' => false, 'message' => 'Nenhuma placa foi fornecida para a busca.']);
        exit;
    }

    try {
        $sql = "SELECT 
                    v.placa, 
                    m.nome_modelo AS modelo, 
                    c.nome_cor AS cor
                FROM veiculos v
                INNER JOIN modelos m ON v.fk_id_modelo = m.id_modelo
                INNER JOIN cores c ON v.fk_id_cor = c.id_cor
                WHERE v.placa = :placa
                LIMIT 50"; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':placa' => $placa]);

        // Obtém os resultados como array associativo
        $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($veiculos)) {
            // Retorna um JSON indicando que nenhum veículo foi encontrado
            echo json_encode(['success' => true, 'data' => [], 'message' => 'Nenhum veículo encontrado com essa placa.']);
        } else {
            // Retorna os veículos encontrados
            echo json_encode(['success' => true, 'data' => $veiculos]);
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    // Retorna um erro caso o método HTTP seja diferente de GET
    echo json_encode(['success' => false, 'message' => 'Método HTTP inválido. Use GET.']);
}

?>