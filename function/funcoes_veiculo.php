<<?php 




function buscarProprietario ($pdo, $nomeProprie) {
    $sqlCheck = "SELECT id_pessoa FROM pessoas WHERE nome_pessoa = :nome";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([':nome' => $nomeProprie]);

    if ($stmtCheck->rowCount() > 0) {
        $proprietario = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $proprietario['id_pessoa'];
    } else {
        echo "Proprietário não cadastrado.";
    }
}


function cadastrarCor($pdo, $cor) {
    $sql = "SELECT id_cor FROM cores WHERE nome_cor = :cor";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':cor' => $cor]);
    
    if ($stmtCheck->rowCount() > 0) {
        $cor = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        return $cor['id_cor'];
    }

    $sqlInsert = "INSERT INTO cores(nome_cor) VALUES (:cor)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':cor' => $cor]);

    return $pdo->lastInsertId();
}

function cadastrarModelo($pdo, $nomeModelo) {
    $sql = "SELECT id_modelo FROM modelos WHERE nome_modelo = :modelo";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':modelo' => $nomeModelo]);

    if($stmtCheck->rowCount() > 0) {
        $modelo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $modelo['id_modelo'];
    }

    $sqlInsert = "INSERT INTO modelos(nome_modelo) VALUES (:modelo)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':modelo' => $nomeModelo]);
    return $pdo->lastInsertId();
}

function cadastrarMarcas($pdo, $marca) {
    $sql = "SELECT id_marca FROM marcas WHERE nome_marca = :marca";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':marca' => $marca]);

    if ($stmtCheck->rowCount() > 0) {
        $marca = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $marca['id_marca'];
    }
    $sqlInsert = "INSERT INTO marcas(nome_marca) VALUES (:marca)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':marca' => $marca]);
    return $pdo->lastInsertId();
}

function cadastrarTipoVeiculo($pdo, $tipoVeiculo) {
    $sql = "SELECT id_tipo_veiculo FROM tipos_veiculos WHERE nome_tipo_veiculo = :tipo";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':tipo' => $tipoVeiculo]);

    if ($stmtCheck->rowCount() > 0) {
        $tipo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $tipo['id_tipo_veiculo'];
    }

    $sqlInsert = "INSERT INTO tipos_veiculos(nome_tipo_veiculo) VALUES (:tipo)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':tipo' => $tipoVeiculo]);
    return $pdo->lastInsertId();
}

function cadastrarVeiculo ($pdo, $idProprietario, $placa, $idCor, $idModelo, $idMarca, $idTipoVeiculo) {
    $sql = "SELECT id_veiculo FROM veiculos WHERE placa = :placa";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':placa' => $placa]);

    if ($stmtCheck->rowCount() > 0) {
        $veiculo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $veiculo['id_veiculo'];
    }

    $sqlInsert = "INSERT INTO veiculos(fk_id_pessoa, placa, fk_id_cor, fk_id_tipo_veiculo, fk_id_modelo, fk_id_marca) VALUES (:propri, :placa, :cor, :tipo, :modelo, :marca)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':propri' => $idProprietario,
        ':placa'  => $placa,
        ':cor'    => $idCor,
        ':tipo'   => $idTipoVeiculo,
        ':modelo' => $idModelo,
        ':marca'  => $idMarca
    ]);

    return $pdo->lastInsertId();
}

//FUNCOES PARA UPDATE/DELETE

function readProprietario($pdo, $id) {
    $sql = "SELECT 
            p.nome_pessoa
            FROM 
                veiculos v
            LEFT JOIN
                pessoas p ON p.id_pessoa = v.fk_id_pessoa
            WHERE p.id_pessoa = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $proprietario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $proprietario['nome_pessoa'] ?? null;
}

function readCor($pdo, $id) {
    $sql = "SELECT
            c.nome_cor
            FROM
                veiculos v
            LEFT JOIN 
                cores c ON c.id_cor = v.fk_id_cor
            WHERE c.id_cor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]); 

    $cor = $stmt->fetch(PDO::FETCH_ASSOC);
    return $cor['nome_cor'];
}

function readTipoVeiculo($pdo, $id) {
    $sql = "SELECT 
            t.nome_tipo_veiculo
            FROM 
                veiculos v
            LEFT JOIN
                tipos_veiculos t ON t.id_tipo_veiculo = v.fk_id_tipo_veiculo
            WHERE 
                t.id_tipo_veiculo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $tipoVeiculo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tipoVeiculo['nome_tipo_veiculo'];
}

function readModelo($pdo, $id) {
    $sql = "SELECT
            m.nome_modelo
            FROM 
                veiculos v
            LEFT JOIN
                modelos m ON m.id_modelo = v.fk_id_modelo
            WHERE 
                m.id_modelo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $modelo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $modelo['nome_modelo'];
}

function readMarca($pdo, $id) {
    $sql = "SELECT 
            m.nome_marca
            FROM 
                veiculos v
            LEFT JOIN
                marcas m ON m.id_marca = v.fk_id_marca
            WHERE
                m.id_marca = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $marca = $stmt->fetch(PDO::FETCH_ASSOC);
    return $marca['nome_marca'];
}

function readVeiculo($pdo, $id) {
    $sql = "SELECT 
            placa,
            status
            FROM 
            veiculos 
            WHERE 
            id_veiculo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $veiculo;
}

function readNovoProprietario($pdo, $nome) {
    $sql = "SELECT
            id_pessoa
            FROM
            pessoas
            WHERE nome_pessoa = :nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);

    $proprietario = $stmt->fetch(PDO::FETCH_ASSOC);
    return $proprietario['id_pessoa'] ?? null;
}

function updateProprietario($pdo, $idPessoa, $idVeiculo) {
    $sql = "UPDATE veiculos
            SET fk_id_pessoa = :idPessoa
            WHERE 
            id_veiculo = :idVeiculo";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':idPessoa' => $idPessoa,
        ':idVeiculo' => $idVeiculo
    ]);
    
}

function updateVeiculos($pdo, $idVeiculo, $idCor = null, $idTipoVeiculo = null, $idModelo = null, $idMarca = null, $placa = null, $status = null) {
    // Array para armazenar as partes da consulta e os parâmetros
    $fields = [];
    $params = [':idVeiculo' => $idVeiculo];

    // Adiciona os campos e parâmetros somente se eles não forem nulos
    if ($idCor !== null) {
        $fields[] = "fk_id_cor = :idCor";
        $params[':idCor'] = $idCor;
    }
    if ($idTipoVeiculo !== null) {
        $fields[] = "fk_id_tipo_veiculo = :idTipoVeiculo";
        $params[':idTipoVeiculo'] = $idTipoVeiculo;
    }
    if ($idModelo !== null) {
        $fields[] = "fk_id_modelo = :idModelo";
        $params[':idModelo'] = $idModelo;
    }
    if ($idMarca !== null) {
        $fields[] = "fk_id_marca = :idMarca";
        $params[':idMarca'] = $idMarca;
    }
    if ($placa !== null) {
        $fields[] = "placa = :placa";
        $params[':placa'] = $placa;
    }
    if ($status !== null) {
        $fields[] = "status = :status";
        $params[':status'] = $status;
    }

    // Se nenhum campo for atualizado, retorna falso
    if (empty($fields)) {
        return false;
    }

    // Junta os campos para criar a parte SET da consulta
    $setClause = implode(', ', $fields);

    // Monta a consulta SQL completa
    $sql = "UPDATE veiculos SET $setClause WHERE id_veiculo = :idVeiculo";
    $stmt = $pdo->prepare($sql);

    // Executa a consulta com os parâmetros
    return $stmt->execute($params);
}

function deleteVeiculo($pdo, $id) {
    $sql = "DELETE FROM veiculos WHERE id_veiculo = :id";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([':id' => $id]);

}

?>