<?php 

//USAR NO PRODUTO
function inserirMarcaProduto($pdo, $nomeMarca) {
    $sql = "INSERT INTO marcas_servicos_produtos(nome_marca_produto) VALUES (:nome)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nomeMarca]);

    return $pdo->lastInsertId();
}   

function inserirProduto($pdo, $nome, $descr, $valor, $quant, $idMarca) {
    $sql = "INSERT INTO servicos_produtos(nome_servico_produto, descricao, valor_servico_produto, quantidade, fk_id_marca_produto, fk_id_tipo_servico) VALUES (:nome, :descr, :valor, :quant, :idMarca, 2)";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':nome' => $nome,
        ':descr' => $descr,
        ':valor' => $valor,
        ':quant' => $quant,
        ':idMarca' => $idMarca
    ]);
}

//PARA UPDATE
function verifica($pdo, $idProduto) {
    $sql = "SELECT 
                m.nome_marca_produto,
                p.nome_servico_produto,
                p.descricao,
                p.valor_servico_produto,
                p.quantidade
            FROM 
                servicos_produtos p
            LEFT JOIN 
                marcas_servicos_produtos m ON m.id_marca_produto = p.fk_id_marca_produto
            WHERE 
                p.id_servico_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $idProduto]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado;
}

function updateMarca($pdo, $nome, $id) {
    $sql = "UPDATE 
                marcas_servicos_produtos
            SET 
                nome_marca_produto = :nome
            WHERE
                id_marca_produto = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':nome' => $nome
    ]);
}

function updateProduto($pdo, $id, $nome = null, $descr = null, $valor = null, $quant = null, $nomeMarca = null) {

    $veriNome = false;
    $veriDescr = false;
    $veriValor = false;
    $veriQuant = false;
    $veriMarca = false;
    
    if ($nome !== null) {
        $sqlNome = "UPDATE
                        servicos_produtos
                    SET
                        nome_servico_produto = :nome
                    WHERE
                        id_servico_produto = :id AND fk_id_tipo_servico = 2";
        $stmtNome = $pdo->prepare($sqlNome);
        if ($stmtNome->execute([
            ':id' => $id,
            ':nome' => $nome
        ])) {
            $veriNome = true;
        }
    }

    if ($descr !== null) {
        $sqlDescr = "UPDATE
                        servicos_produtos
                    SET
                        descricao = :descr
                    WHERE
                        id_servico_produto = :id AND fk_id_tipo_servico = 2";
        $stmtDescr = $pdo->prepare($sqlDescr);
        if ($stmtDescr->execute([
            ':id' => $id,
            ':descr' => $descr
        ])) {
            $veriDescr = true;
        }
    }

    if ($valor !== null) {
        $sqlValor = "UPDATE
                        servicos_produtos
                    SET
                        valor_servico_produto = :valor
                    WHERE
                        id_servico_produto = :id AND fk_id_tipo_servico = 2";
        $stmtValor = $pdo->prepare($sqlValor);
        if ($stmtValor->execute([
            ':id' => $id,
            ':valor' => $valor
        ])) {
            $veriValor = true;
        }
    }

    if ($quant !== null) {
        $sqlQuant = "UPDATE
                        servicos_produtos
                    SET
                        quantidade = :quant
                    WHERE
                        id_servico_produto = :id AND fk_id_tipo_servico = 2";
        $stmtQuant = $pdo->prepare($sqlQuant);
        if ($stmtQuant->execute([
            ':id' => $id,
            ':quant' => $quant
        ])) {
            $veriQuant = true;
        }
    }

    if ($nomeMarca !== null) {
        $idMarca = inserirMarcaProduto($pdo, $nomeMarca); 
        $sqlMarca = "UPDATE
                        servicos_produtos
                    SET 
                        fk_id_marca_produto = :idMarca
                    WHERE 
                        id_servico_produto = :id AND fk_id_tipo_servico = 2";
        $stmtMarca = $pdo->prepare($sqlMarca);
        if ($stmtMarca->execute([
            ':idMarca' => $idMarca,
            ':id' => $id
            ])) {
            $veriMarca = true;
        }
    }

    $produtoArray = [$veriNome, $veriDescr, $veriValor, $veriQuant, $veriMarca];

    return $produtoArray;
}

function deleteProduto($pdo, $id) {
    $sql = "DELETE FROM servicos_produtos WHERE id_servico_produto = :id AND fk_id_tipo_servico = 2";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
?>