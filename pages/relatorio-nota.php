<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

    
?>

<?php 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    require '../config/config.php';

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
                    p.numero_telefone,

                    v.placa,                 

                    mo.id_modelo,
                    mo.nome_modelo,

                    ma.id_marca,
                    ma.nome_marca,

                    pa.id_pagamento,

                    i.fk_id_manutencao,
                    i.fk_id_servico_produto,
                    i.quantidade,
                    i.valor_uni,
                    
                    sp.nome_servico_produto,
                    sp.descricao,
                    sp.valor_servico_produto,
                    sp.fk_id_tipo_servico, -- Inclui fk_id_tipo_servico diretamente
                    ts.tipo_servico_produto AS tipo_nome -- Nome do tipo (Serviço ou Produto) da tabela tipos_servicos
                    
                FROM 
                    manutencoes m
                LEFT JOIN
                    veiculos v ON v.id_veiculo = m.fk_id_veiculo
                LEFT JOIN 
                    modelos mo ON mo.id_modelo = v.fk_id_modelo
                LEFT JOIN
                    marcas ma ON ma.id_marca = v.fk_id_marca
                LEFT JOIN 
                    pessoas p ON p.id_pessoa = v.fk_id_pessoa
                LEFT JOIN
                    pagamentos pa ON m.id_manutencao = pa.fk_id_manutencao
                LEFT JOIN
                    itens_manutencoes_servicos i ON m.id_manutencao = i.fk_id_manutencao
                LEFT JOIN
                    servicos_produtos sp ON i.fk_id_servico_produto = sp.id_servico_produto
                LEFT JOIN
                    tipos_servicos_produtos ts ON sp.fk_id_tipo_servico = ts.id_tipo_servico_produto -- JOIN para obter o tipo do serviço/produto
                WHERE
                    m.id_manutencao = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultados = [];              // Array para dados gerais da manutenção
        $itens_manutencao = [           // Array para itens de manutenção, separados por tipo
            'servicos' => [],           // Subarray para serviços
            'produtos' => []            // Subarray para produtos
        ];
        $ids_adicionados = [];          // Array auxiliar para rastrear IDs de itens já adicionados
        
        foreach ($dados as $row) {
            // Popula o array $resultados apenas uma vez com os dados gerais
            if (empty($resultados)) {
                $resultados = [
                    "id_manutencao" => $row['id_manutencao'],
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
                            "nome_pessoa" => $row["nome_pessoa"],
                            "numero_telefone" => $row["numero_telefone"]
                        ],
                        "marca" => [
                            "id_marca" => $row['id_marca'],
                            "nome_marca" => $row['nome_marca']
                        ]
                    ],
                    "pagamentos" => []
                ];
            }

            // Adiciona os pagamentos, garantindo que não sejam duplicados
            if (!empty($row['id_pagamento']) && !in_array($row['id_pagamento'], array_column($resultados['pagamentos'], 'id_pagamento'))) {
                $resultados['pagamentos'][] = [
                    "id_pagamento" => $row["id_pagamento"]
                ];
            }

            // Verifica e adiciona itens únicos ao array $itens_manutencao, separados por tipo
            if (!empty($row['fk_id_servico_produto']) && !in_array($row['fk_id_servico_produto'], $ids_adicionados)) {
                $item = [
                    "fk_id_servico_produto" => $row["fk_id_servico_produto"],
                    "nome_servico_produto" => $row["nome_servico_produto"],
                    "descricao" => $row["descricao"],
                    "quantidade" => $row["quantidade"],
                    "valor_uni" => $row["valor_uni"],
                    "valor_servico_produto" => $row["valor_servico_produto"]
                ];

                // Verifica se fk_id_tipo_servico existe e separa o item por tipo
                if (isset($row['fk_id_tipo_servico'])) {
                    if ($row['fk_id_tipo_servico'] == 1) {
                        $itens_manutencao['servicos'][] = $item;
                    } elseif ($row['fk_id_tipo_servico'] == 2) {
                        $itens_manutencao['produtos'][] = $item;
                    }
                }

                $ids_adicionados[] = $row['fk_id_servico_produto']; // Armazena o ID do item para evitar duplicatas
            }
        }

        // Exibe os dados gerais e os itens separados para depuração
        //var_dump($resultados); // Dados gerais da manutenção
        //var_dump($itens_manutencao['produtos'][0]); // Itens de manutenção, separados em 'servicos' e 'produtos'

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Nota - CarCheck</title>
    <link rel="stylesheet" href="../css/relatorio-nota.css">
    
    <style>
        .btn-imprimir {
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .btn-imprimir:hover {
            background-color: #45a049; /* Verde mais escuro */
        }
            body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Alinha no topo */
        padding: 20px;
        padding-top: 135px;
        width: 100%; /* Garante que ocupa toda a largura da tela */
        overflow-x: hidden; /* Evita rolagem horizontal */
    }
    </style>
</head>
<?php
include '../includes/header.php';
?>
<body>
<div id="conteudo">
    
        <div class="container-relatorio">
            <h1 class="titulo-relatorio">Relatório de Nota</h1>
    
            <div class="informacoes-gerais">
                <h2>Dados do Cliente</h2>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($resultados['veiculo']['proprietario']['nome_pessoa']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($resultados['veiculo']['proprietario']['numero_telefone']); ?></p>
            </div>
    
            <div class="informacoes-gerais">
                <h2>Dados do Veículo</h2>
                <p><strong>Modelo:</strong> <?php echo htmlspecialchars($resultados['veiculo']['modelo']['nome_modelo']); ?></p>
                <p><strong>Placa:</strong> <?php echo htmlspecialchars($resultados['veiculo']['placa']); ?></p>
                <p><strong>Marca:</strong> <?php echo htmlspecialchars($resultados['veiculo']['marca']['nome_marca']); ?></p>
                <p><strong>Km:</strong> <?php echo htmlspecialchars($resultados['km']); ?></p>
            </div>
    
            <div class="informacoes-gerais">
                <h2>Dados da Manutenção</h2>
                <p><strong>Codigo:</strong> <?php echo htmlspecialchars($resultados['codigo']); ?></p>
                <p><strong>Data/Hora:</strong> <?php
                    $data_saida = DateTime::createFromFormat('Y-m-d H:i:s', $resultados['time_saida']);
                    echo $data_saida ? $data_saida->format('d/m/Y H:i') : 'Data inválida';
                ?></p>
                <p><strong>Defeito Reclamado:</strong> <?php echo htmlspecialchars($resultados['defeito']); ?></p>
            </div>
    
            <?php
                $valorTotalServicos = 0; // Inicializa a variável antes do loop
            ?>
            <div class="informacoes-servicos">
                <h2>Serviços Realizados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens_manutencao['servicos'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome_servico_produto']); ?></td>
                            <td><?php echo htmlspecialchars($item['descricao']); ?></td>
                            <td>R$ <?php echo number_format($item['valor_servico_produto'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                            $valorTotalServicos += $item['valor_servico_produto'];
                        ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    
            <?php
                $valorTotalProdutos = 0; // Inicializa a variável antes do loop
            ?>
            <div class="informacoes-pecas">
                <h2>Peças Utilizadas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Peça</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens_manutencao['produtos'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome_servico_produto']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($item['valor_uni'], 2, ',', '.'); ?></td>
                            <td>R$ <?php
                                        $valorTotal = $item['quantidade'] * $item['valor_uni'];
                                        echo number_format($valorTotal, 2, ',', '.');
                                    ?></td>
                        </tr>
                        <?php
                            $valorTotalProdutos += $valorTotal;
                        ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    
            <div class="informacoes-totais">
                <h2>Total</h2>
                <p><strong>Valor Total dos Serviços: R$</strong> <?php echo number_format($valorTotalServicos, 2, ',', '.');?></p>
                <p><strong>Valor Total das Peças: R$</strong> <?php echo number_format($valorTotalProdutos, 2, ',', '.');?></p>
                <p class="valor-final"><strong>Valor Final: R$</strong> <?php echo $resultados['valor_total']?></p>
            </div>
        </div>

        <button class="btn-imprimir" id="botaoImprimir">Imprimir</button>
    
</div>


<?php include '../includes/imprimir.php'; ?>


</body>
</html>
<?php endif; ?>