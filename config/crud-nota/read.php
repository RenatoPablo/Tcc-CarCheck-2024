<?php 

require '../config.php';

try {
    $sql = "SELECT
                m.id_manutencao,
                m.time_saida,
                m.km,
                m.defeito,
                m.valor_total,
                m.codigo,
                m.faturamento,
                
                p.id_pessoa,
                p.nome_pessoa,

                v.placa,                 

                mo.id_modelo,
                mo.nome_modelo,

                pa.id_pagamento,

                i.fk_id_manutencao,
                i.fk_id_servico_produto,
                i.quantidade,
                i.valor_uni,
                
                sp.nome_servico_produto,
                sp.descricao,
                sp.valor_servico_produto
                
            FROM 
                manutencoes m
            LEFT JOIN
                veiculos v ON v.id_veiculo = m.fk_id_veiculo
            LEFT JOIN 
                modelos mo ON mo.id_modelo = v.fk_id_modelo
            LEFT JOIN 
                pessoas p ON p.id_pessoa = v.fk_id_pessoa
            LEFT JOIN
                pagamentos pa ON m.id_manutencao = pa.fk_id_manutencao
            LEFT JOIN
                itens_manutencoes_servicos i ON m.id_manutencao = i.fk_id_manutencao
            LEFT JOIN
                servicos_produtos sp ON i.fk_id_servico_produto = sp.id_servico_produto
            ORDER BY
                time_saida DESC"; // Join com a tabela de serviços/produtos
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $manutencoes = [];
    
    foreach ($resultados as $row) {
        $id_manutencao = $row['id_manutencao'];
        
        // Se a manutenção ainda não estiver no array $manutencoes, adiciona-a
        if (!isset($manutencoes[$id_manutencao])) {
            $manutencoes[$id_manutencao] = [
                "id_manutencao" => $id_manutencao,
                "codigo" => $row['codigo'],
                "time_saida" => $row["time_saida"],
                "km" => $row["km"],
                "defeito" => $row["defeito"],
                "valor_total" => $row["valor_total"],
                "faturamento" => $row['faturamento'],
                "veiculo" => [
                    "placa" => $row["placa"],
                    "modelo" => [
                        "id_modelo" => $row["id_modelo"],
                        "nome_modelo" => $row["nome_modelo"]
                    ],
                    "proprietario" => [
                        "id_pessoa" => $row["id_pessoa"],
                        "nome_pessoa" => $row["nome_pessoa"]
                    ]
                ],
                "pagamentos" => [],
                "itens_manutencao" => []
            ];
        }

        // Adiciona os pagamentos, garantindo que não sejam duplicados
        if (!empty($row['id_pagamento']) && !in_array($row['id_pagamento'], array_column($manutencoes[$id_manutencao]['pagamentos'], 'id_pagamento'))) {
            $manutencoes[$id_manutencao]['pagamentos'][] = [
                "id_pagamento" => $row["id_pagamento"]
            ];
        }

        // Adiciona os itens de manutenção sem duplicação
        if (!empty($row['fk_id_servico_produto']) && !in_array($row['fk_id_servico_produto'], array_column($manutencoes[$id_manutencao]['itens_manutencao'], 'fk_id_servico_produto'))) {
            $manutencoes[$id_manutencao]['itens_manutencao'][] = [
                "fk_id_servico_produto" => $row["fk_id_servico_produto"],
                "nome_servico_produto" => $row["nome_servico_produto"],
                "descricao" => $row["descricao"],
                "quantidade" => $row["quantidade"],
                "valor_uni" => $row["valor_uni"],
                "valor_servico_produto" => $row["valor_servico_produto"]
            ];
        }
    }

    // Converte o array de manutenções para JSON e imprime
    echo json_encode(array_values($manutencoes));

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
} 
