<?php 


function inserirServico($pdo, $nome, $descr, $valor, $tipoServico) {
    $sql = "INSERT INTO servicos_produtos(nome_servico_produto, descricao, valor_servico_produto, fk_id_tipo_servico) VALUES (:nome, :descr, :valor, :idTipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome, 
        ':descr' => $descr, 
        ':valor' => $valor,
        ':idTipo' => $tipoServico 
    ]);
    return $pdo->lastInsertId();
}

//FUNCAO PARA VERIFICAR O BANCO
function verificaServico($pdo, $id) {
    
        $sql = "SELECT 
                    nome_servico_produto, descricao, valor_servico_produto
                FROM
                    servicos_produtos
                WHERE
                    id_servico_produto = :id AND fk_id_tipo_servico = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        return $servico;
    
}

//FUNCAO PARA UPDATE
function updateServico($pdo, $id, $nome = null, $descr = null, $valor = null) {
    $veriNome = false;
    $veriDescr = false;
    $veriValor = false;

    if($nome !== null) {
        $sql = "UPDATE 
                    servicos_produtos
                SET 
                    nome_servico_produto = :nome
                WHERE 
                    id_servico_produto = :id AND fk_id_tipo_servico = 1";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':id' => $id,
            ':nome' => $nome
        ])) {
            $veriNome = true;
        }

    }
    if($descr !== null) {
        $sql = "UPDATE 
                    servicos_produtos
                SET 
                    descricao = :descr
                WHERE 
                    id_servico_produto = :id AND fk_id_tipo_servico = 1";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':id' => $id,
            ':descr' => $descr
        ])) {
            $veriDescr = true;
        }

    }
    if($valor !== null) {
        $sql = "UPDATE 
                    servicos_produtos
                SET 
                    valor_servico_produto = :valor
                WHERE 
                    id_servico_produto = :id AND fk_id_tipo_servico = 1";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            ':id' => $id,
            ':valor' => $valor
        ])) {
            $veriValor = true;
        }

    }

    $servicoArray = [$veriNome, $veriDescr, $veriValor];

    return $servicoArray;
}

function deleteServico($pdo, $id) {
    $sql = "DELETE FROM servicos_produtos WHERE id_servico_produto = :id AND fk_id_tipo_servico = 1";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

?>