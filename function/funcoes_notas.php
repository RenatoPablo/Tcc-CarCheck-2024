<?php 


function obterIdVeiculo($pdo, $placa) {
    $sql = "SELECT id_veiculo FROM veiculos WHERE placa = :placa";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':placa' => $placa]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $veiculo['id_veiculo'];
}

function cadastrarManutencao($pdo, $time, $km, $defeito, $idVeiculo, $valor) {

    $horaFormatada = date('Y-m-d H:i:s', strtotime($time));

    $codigoManutencao = 'OS-' . date('YmdHis');

    $fatura = false;

    $sql = "INSERT INTO manutencoes(time_saida, km, defeito, fk_id_veiculo, valor_total, codigo, faturamento) VALUES (:hora, :km, :defeito, :idVeiculo, :valor, :codigo, :fatura)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':hora' => $horaFormatada,
        ':km'   => $km,
        ':defeito' => $defeito,
        ':idVeiculo' => $idVeiculo,
        ':valor' => $valor,
        ':codigo' => $codigoManutencao,
        ':fatura' => $fatura
    ]);
    return $pdo->lastInsertId();
}

function atualizarValorTotal($pdo, $valorTotal, $idManutencao) {
    $sql = "UPDATE manutencoes SET valor_total = :valor WHERE id_manutencao = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':valor' => $valorTotal,
        ':id' => $idManutencao
    ]);
}



function cadastrarItensManutencao($pdo, $idManutencao, $idServicoProduto, $valorUnitario, $quantidade = null) {
    
    
    
    $sql = "INSERT INTO itens_manutencoes_servicos(fk_id_manutencao, fk_id_servico_produto, quantidade, valor_uni) 
            VALUES (:idManutencao, :idServicoProduto, :quant, :valorUni)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idManutencao' => $idManutencao,
        ':idServicoProduto' => $idServicoProduto,
        ':quant' => $quantidade,
        ':valorUni' => $valorUnitario
    ]);

    return $pdo->lastInsertId();
}

function atualizarEstoqueProduto($pdo, $idProduto, $quantidadeUtilizada) {
    // Consulta para obter a quantidade atual do estoque
    $sql = "SELECT quantidade, nome_servico_produto AS nome FROM servicos_produtos WHERE id_servico_produto = :idProduto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idProduto' => $idProduto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produto && isset($produto['quantidade'])) {
        // Calcula o novo valor de estoque
        $novaQuantidade = $produto['quantidade'] - $quantidadeUtilizada;
        $mensagem = null;
        // Verifica se a quantidade nova não é negativa
        if ($novaQuantidade < 0) {
            // Armazena a mensagem de erro na variável de sessão
            $mensagem = "Quantidade insuficiente no estoque para o produto: " . $produto['nome'];
    
            // Código para excluir a última manutenção adicionada
            $sql = "DELETE FROM manutencoes WHERE id_manutencao = (SELECT MAX(id_manutencao) FROM manutencoes)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
    
            return $mensagem;
        }

        // Atualiza o estoque no banco de dados
        $sqlUpdate = "UPDATE servicos_produtos SET quantidade = :novaQuantidade WHERE id_servico_produto = :idProduto";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':novaQuantidade' => $novaQuantidade,
            ':idProduto' => $idProduto
        ]);
        return $mensagem;
    } else {
        throw new Exception("Produto com ID: $idProduto não encontrado no estoque.");
    }
}

function insertPagamento($pdo, $codigo, $valor, $data, $parcela, $idForma, $idManutencao) {
    try {
        // Prepara a consulta de inserção
        $sql = "INSERT INTO pagamentos (codigo, valor_parcela, data_parcela, num_parcela, fk_id_forma_pagamento, fk_id_manutencao) 
                VALUES (:codi, :valor, :data, :num, :idForma, :idManutencao)";
        $stmt = $pdo->prepare($sql);

        // Executa a consulta
        $stmt->execute([
            ':codi' => $codigo,
            ':valor' => $valor,
            ':data' => $data,
            ':num' => $parcela,
            ':idForma' => $idForma,
            ':idManutencao' => $idManutencao
        ]);

        // Exibe mensagem de sucesso para depuração
        echo "Pagamento inserido com sucesso. Código: $codigo. Parcela: $parcela. Valor: $valor. Data: $data.<br>";
    } catch (PDOException $e) {
        echo "Erro ao inserir pagamento: " . $e->getMessage();
    }
}




function readManutencao($pdo, $idManutencao) {
    $sql = "SELECT * FROM manutencoes WHERE id_manutencao = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $idManutencao]);

    $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultados;
}

function updateManutencao($pdo, $idManutencao, $idModelo, $idPessoa, $codigo, $km = null, $defeito = null, $placa = null, $modelo = null, $nomePessoa = null) {
    $kmVerifica = false;
    $defeitoVerifica = false;
    $placaVerifica = false; 
    $modeloVerifica = false;
    $pessoaVerifica = false;

    if ($km !== null) {
        $sql = "UPDATE manutencoes SET km = :km WHERE id_manutencao = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':km' => $km,
            ':id' => $idManutencao
        ]);
        $kmVerifica = true;
    }

    if ($defeito !== null) {
        $sql = "UPDATE manutencoes SET defeito = :defeito WHERE id_manutencao = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':defeito' => $defeito,
            ':id' => $idManutencao
        ]);
        $defeitoVerifica = true;
    }

    if ($placa !== null) {
        $sql = "UPDATE manutencoes SET placa = :placa WHERE id_manutencao = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':placa' => $placa,
            ':id' => $idManutencao
        ]);
        $placaVerifica = true;
    } 

    // if ($modelo !== null) {
    //     $sql = "UPDATE modelos SET nome_modelo = :modelo WHERE id_modelo = :id";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute([
    //         ':modelo' => $modelo,
    //         ':id' => $idModelo
    //     ]);
    //     $modeloVerifica = true;
    // }

    $array = [
        $kmVerifica,
        $defeitoVerifica,
        $placaVerifica
    ];

    return $array;
    
}

function faturamentoTrue($pdo, $idManutencao) {
    $sql = "UPDATE manutencoes SET faturamento = true WHERE id_manutencao = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $idManutencao]);
}





?>