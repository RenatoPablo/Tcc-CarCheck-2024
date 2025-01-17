<?php
require '../config.php';

if (isset($_POST['idManutencao'], $_POST['todosItens'])) {
    $idManutencao = intval($_POST['idManutencao']);
    $todosItens = json_decode($_POST['todosItens'], true);
    if (!is_array($todosItens)) {
        $todosItens = [];
    }
    $time_saida = $_POST['hora'] ?? null;
    $km = $_POST['km'] ?? null;
    $defeito = $_POST['defeito'] ?? null;
    $valor_total = floatval($_POST['valor_total'] ?? 0);

    // Calcular valor total se não foi enviado ou está vazio
    // if (empty($valor_total)) {
    //     $valor_total = 0;
    //     foreach ($todosItens as $item) {
    //         $itemQuantidade = isset($item['quantidade']) ? (float) $item['quantidade'] : 0;
    //         $itemValorUnitario = isset($item['valor']) ? (float) $item['valor'] : (float) $item['valor_uni'];
    //         $valor_total += $itemQuantidade * $itemValorUnitario;
    //     }
    // }
    var_dump($idManutencao);
    var_dump($valor_total);
    try {
        // Inicia uma transação para garantir que todas as operações sejam realizadas com segurança
        $pdo->beginTransaction();

        // Etapa 1: Atualizar a tabela `manutencoes`
        $sqlUpdateManutencao = "UPDATE manutencoes SET time_saida = :time_saida, km = :km, defeito = :defeito, valor_total = :valor_total WHERE id_manutencao = :id_manutencao";
        $stmtManutencao = $pdo->prepare($sqlUpdateManutencao);
        $veri = $stmtManutencao->execute([
            ':time_saida' => $time_saida,
            ':km' => $km,
            ':defeito' => $defeito,
            ':valor_total' => $valor_total,
            ':id_manutencao' => $idManutencao
        ]);
        var_dump($veri);
        // Etapa 2: Verifica se há novos itens enviados
        if (count($todosItens) > 0) {
            // Remove todos os itens associados à manutenção antes de inserir os novos
            $sqlDeleteItens = "DELETE FROM itens_manutencoes_servicos WHERE fk_id_manutencao = :id_manutencao";
            $stmtDeleteItens = $pdo->prepare($sqlDeleteItens);
            $stmtDeleteItens->execute([':id_manutencao' => $idManutencao]);

            // Etapa 3: Insere os novos itens
            $sqlInsertItem = "INSERT INTO itens_manutencoes_servicos (fk_id_manutencao, fk_id_servico_produto, quantidade, valor_uni) VALUES (:id_manutencao, :fk_id_servico_produto, :quantidade, :valor_uni)";
            $stmtInsertItem = $pdo->prepare($sqlInsertItem);

            foreach ($todosItens as $item) {
                if (isset($item['id'])) {
                    // Insere o item como produto já existente no sistema
                    $stmtInsertItem->execute([
                        ':id_manutencao' => $idManutencao,
                        ':fk_id_servico_produto' => $item['id'],
                        ':quantidade' => $item['quantidade'],
                        ':valor_uni' => $item['valor']
                    ]);
                } else if (isset($item['fk_id_servico_produto'])) {
                    // Insere o item como novo serviço ou produto na manutenção
                    $stmtInsertItem->execute([
                        ':id_manutencao' => $idManutencao,
                        ':fk_id_servico_produto' => $item['fk_id_servico_produto'],
                        ':quantidade' => $item['quantidade'],
                        ':valor_uni' => $item['valor_uni']
                    ]);
                }
            }
        }

        // Confirma a transação após todos os updates/inserts
        $pdo->commit();
        
        $_SESSION['mensagem'] = "Manutenção atualizada com sucesso.";
        header('Location: ../../pages/ordem.php');
        exit;
        

    } catch (PDOException $e) {
        // Em caso de erro, desfaz a transação
        $pdo->rollBack();
        $_SESSION['mensagem'] = "Erro na atualização de manutenção.";
        header('Location: ../../pages/ordem.php');
        exit;
        
    }
} else {
    echo "Dados insuficientes para atualização.";
}
?>