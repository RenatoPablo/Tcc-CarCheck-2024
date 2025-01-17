<?php



function cadastrarCargo($pdo, $nomeCargo) {
    $sql = "SELECT id_cargo FROM cargos WHERE nome_cargo = :cargo";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':cargo' => $nomeCargo]);

    if ($stmtCheck->rowCount() > 0) {
        $cargo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $cargo['id_cargo'];
    }

    $sqlInsert = "INSERT INTO cargos(nome_cargo) VALUES (:cargo)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':cargo' => $nomeCargo]);
    return $pdo->lastInsertId();
}

function cadastrarFuncoes($pdo, $nomeFuncao) {
    $sql = "SELECT id_funcao FROM funcoes WHERE nome_funcao = :funcoes";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':funcoes' => $nomeFuncao]);

    if ($stmtCheck->rowCount() > 0) {
        $funcao = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $funcao['id_funcao'];
    }
    $sqlInsert = "INSERT INTO funcoes(nome_funcao) VALUES (:funcao)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([':funcao' => $nomeFuncao]);
    return $pdo->lastInsertId();
}

function cadastrarFunci($pdo, $id_pessoa, $id_cargo, $id_funcao) {
    $sql = "SELECT f.id_funcionario 
            FROM funcionarios f 
            WHERE f.fk_id_pessoa = :pessoa";
    $stmtCheck = $pdo->prepare($sql);
    $stmtCheck->execute([':pessoa' => $id_pessoa]);

    if ($stmtCheck->rowCount() > 0) {
        $funcionario = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        return $funcionario['id_funcionario'];
    }
    $sqlInsert = "INSERT INTO funcionarios(fk_id_cargo, fk_id_funcao, fk_id_pessoa) VALUES (:cargo, :funcao, :pessoa)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':cargo'  => $id_cargo,
        ':funcao' => $id_funcao,
        ':pessoa' => $id_pessoa
    ]);
    return $pdo->lastInsertId();
}

function readCargo($pdo, $id) {
    $sql = "SELECT nome_cargo FROM cargos WHERE id_cargo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetchColumn();
}

// Função para atualizar o cargo
function updateCargo($pdo, $cargoNome, $id) {
    $stmt = $pdo->prepare("UPDATE cargos SET nome_cargo = :nome WHERE id_cargo = :id");
    $stmt->bindParam(':nome', $cargoNome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute(); // Retorna true em caso de sucesso
}

function readFuncao($pdo, $id) {
    $sql = "SELECT nome_funcao FROM funcoes WHERE id_funcao = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetchColumn();
}

// Função para atualizar a função
function updateFuncao($pdo, $funcaoNome, $id) {
    $stmt = $pdo->prepare("UPDATE funcoes SET nome_funcao = :nome WHERE id_funcao = :id");
    $stmt->bindParam(':nome', $funcaoNome);
    $stmt->bindParam(':id', $id);   
    return $stmt->execute(); // Retorna true em caso de sucesso
}



function updatePessoa($pdo, $id, $nome, $email, $telefone, $data_nascimento, $fotoNome = null) {
    if ($fotoNome) {
        $sql = "UPDATE pessoas SET nome_pessoa = :nome, endereco_email = :email, numero_telefone = :telefone, data_nasc = :data_nascimento, foto = :foto WHERE id_pessoa = :id";
    } else {
        $sql = "UPDATE pessoas SET nome_pessoa = :nome, endereco_email = :email, numero_telefone = :telefone, data_nasc = :data_nascimento WHERE id_pessoa = :id";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    if ($fotoNome) {
        $stmt->bindParam(':foto', $fotoNome);
    }
    $stmt->execute();

}

function deleteFuncionario($pdo, $id) {
    $sql = "DELETE FROM funcionarios WHERE id_funcionario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}
?>