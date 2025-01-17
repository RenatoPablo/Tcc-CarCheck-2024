<?php 

function inserirFornecedor($pdo, $nome, $razao, $ie, $cnpj) {
    $sqlInsert = "INSERT INTO fornecedores(nome_fantasia, razao_social, ie, cnpj) VALUES (:nome, :razao, :ie, :cnpj)";
    $stmt = $pdo->prepare($sqlInsert);
    return $stmt->execute([
        ':nome' => $nome,
        ':razao' => $razao,
        ':ie' => $ie,
        'cnpj' => $cnpj
    ]);
}

function readFornecedor($pdo, $id) {
    $sql = "SELECT * FROM fornecedores WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultados;
}

function updateFornecedor($pdo, $id, $nome = null, $razao = null, $ie = null, $cnpj = null) {
    
    $veriNome = false;
    $veriRazao = false;
    $veriIe = false;
    $veriCnpj = false;


    if ($nome !== null) {
        $sqlNome = "UPDATE fornecedores 
                    SET nome_fantasia = :nome 
                    WHERE id_fornecedor = :id";
        $stmt = $pdo->prepare($sqlNome);
        if ($stmt->execute([
            ':nome' => $nome,
            ':id' => $id
        ])) {
            $veriNome = true;
        }
    }

    if ($razao !== null) {
        $sqlRazao = "UPDATE fornecedores 
                    SET razao_social = :razao 
                    WHERE id_fornecedor = :id";
        $stmt = $pdo->prepare($sqlRazao);
        if ($stmt->execute([
            ':razao' => $razao,
            ':id' => $id
        ])) {
            $veriRazao = true;
        }
    }

    if ($ie !== null) {
        $sqlIe = "UPDATE fornecedores 
                    SET ie = :ie 
                    WHERE id_fornecedor = :id";
        $stmt = $pdo->prepare($sqlIe);
        if ($stmt->execute([
            ':ie' => $ie,
            ':id' => $id
        ])) {
            $veriIe = true;
        }
    }

    if ($cnpj !== null) {
        $sqlCnpj = "UPDATE fornecedores 
                    SET cnpj = :cnpj 
                    WHERE id_fornecedor = :id";
        $stmt = $pdo->prepare($sqlCnpj);
        if ($stmt->execute([
            ':cnpj' => $cnpj,
            ':id' => $id
        ])) {
            $veriCnpj = true;
        }
    }

    $array = [$veriNome, $veriRazao, $veriIe, $veriCnpj];

    return $array;
}

function deleteFornecedor($pdo, $id) {
    $sql = "DELETE FROM fornecedores WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

?>