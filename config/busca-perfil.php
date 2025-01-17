<?php 


    // print_r($_SESSION);
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");		
    else:
    
    require '../config/config.php';

    try {
        //preparar consulta
        $sql = "
        SELECT p.nome_pessoa, p.endereco_email, p.data_nasc, p.foto, p.numero_telefone, ce.numero_cep, r.nome_rua, g.sexo, n.numero_casa, ci.nome_cidade, b.nome_bairro, co.desc_complemento, po.desc_ponto_ref    
        FROM pessoas p
        INNER JOIN ceps ce ON p.fk_id_cep = ce.id_cep
        INNER JOIN ruas r ON p.fk_id_rua = r.id_rua
        INNER JOIN generos g ON p.fk_id_genero = g.id_genero
        INNER JOIN numeros_casas n ON p.fk_id_numero_casa = n.id_numero_casa
        INNER JOIN cidades ci ON p.fk_id_cidade = ci.id_cidade
        INNER JOIN bairros b ON p.fk_id_bairro = b.id_bairro
        INNER JOIN complementos co ON p.fk_id_complemento = co.id_complemento
        INNER JOIN pontos_referencias po ON p.fk_id_ponto_ref = po.id_ponto_ref
        WHERE nome_pessoa = :nome";
        $stmt = $pdo->prepare($sql);

        //executar consulta
        $stmt->execute([':nome' => $_SESSION['nomeUsuario']]);

        //pega todos os resultados em um array associado
        $resultados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultados) {
            $nome = htmlspecialchars($resultados['nome_pessoa']);
            $email = htmlspecialchars($resultados['endereco_email']);
            $data_nasc = htmlspecialchars($resultados['data_nasc']);
            $foto = htmlspecialchars($resultados['foto']);
            $telefone = htmlspecialchars($resultados['numero_telefone']);
            $cep = htmlspecialchars($resultados['numero_cep']);
            $rua = htmlspecialchars($resultados['nome_rua']);
            $sexo = htmlspecialchars($resultados['sexo']);
            $num_casa = htmlspecialchars($resultados['numero_casa']);
            $cidade = htmlspecialchars($resultados['nome_cidade']);
            $bairro = htmlspecialchars($resultados['nome_bairro']);
            $comple = htmlspecialchars($resultados['desc_complemento']);
            $referencia = htmlspecialchars($resultados['desc_ponto_ref']);
        }
        
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    endif
?>