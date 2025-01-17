<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

    require '../config/config.php';

    $idVeiculo = intval($_GET['id_veiculo']);

    

    $sql = "SELECT 
                v.id_veiculo,
                v.placa,
                ma.nome_marca,
                c.nome_cor,
                ti.nome_tipo_veiculo,
                mo.nome_modelo
            FROM 
                veiculos v 
            LEFT JOIN
                marcas ma ON ma.id_marca = v.fk_id_marca
            LEFT JOIN
                cores c ON c.id_cor = v.fk_id_cor
            LEFT JOIN 
                tipos_veiculos ti ON ti.id_tipo_veiculo = v.fk_id_tipo_veiculo
            LEFT JOIN
                modelos mo ON mo.id_modelo = v.fk_id_modelo
            WHERE 
                v.id_veiculo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $idVeiculo]);
        $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

        
try {
            $sqlServicos = "SELECT 
                                s.id_servico_produto,
                                s.nome_servico_produto AS nome,
                                s.descricao,
                                s.valor_servico_produto AS valor,
                                m.codigo AS manutencao_codigo,
                                m.time_saida AS data,
                                m.defeito
                            FROM 
                                itens_manutencoes_servicos ims
                            INNER JOIN 
                                servicos_produtos s ON ims.fk_id_servico_produto = s.id_servico_produto
                            INNER JOIN 
                                manutencoes m ON ims.fk_id_manutencao = m.id_manutencao
                            INNER JOIN 
                                tipos_servicos_produtos ti ON ti.id_tipo_servico_produto = s.fk_id_tipo_servico
                            WHERE 
                                m.fk_id_veiculo = :id_veiculo AND ti.tipo_servico_produto = 'servico'
                            ORDER BY
                                m.time_saida DESC
                        ";

    // Consulta para buscar produtos relacionados às manutenções do veículo
    $sqlProdutos = "SELECT 
                        s.id_servico_produto,
                        s.nome_servico_produto AS nome,
                        s.descricao,
                        s.valor_servico_produto AS valor,
                        m.codigo AS manutencao_codigo,
                        m.time_saida AS data,
                        ims.quantidade
                    FROM 
                        itens_manutencoes_servicos ims
                    INNER JOIN 
                        servicos_produtos s ON ims.fk_id_servico_produto = s.id_servico_produto
                    INNER JOIN 
                        manutencoes m ON ims.fk_id_manutencao = m.id_manutencao
                    INNER JOIN
                        tipos_servicos_produtos ti ON ti.id_tipo_servico_produto = s.fk_id_tipo_servico
                    WHERE 
                        m.fk_id_veiculo = :id_veiculo AND ti.tipo_servico_produto = 'peca'
                    ORDER BY
                        m.time_saida DESC
                ";

    // Prepara e executa a consulta para serviços
    $stmtServicos = $pdo->prepare($sqlServicos);
    $stmtServicos->bindParam(':id_veiculo', $idVeiculo, PDO::PARAM_INT);
    $stmtServicos->execute();
    $servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

    //var_dump($servicos);

    // Prepara e executa a consulta para produtos
    $stmtProdutos = $pdo->prepare($sqlProdutos);
    $stmtProdutos->bindParam(':id_veiculo', $idVeiculo, PDO::PARAM_INT);
    $stmtProdutos->execute();
    $produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
    
    //var_dump($produtos);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Veículo</title>
    <link rel="stylesheet" href="../css/ficha-veiculo.css">
    <link rel="stylesheet" href="../css/forma-pagamento.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header-cliente.php'; ?>

<main class="ficha-veiculo">
    <div id="conteudo">
        <div class="container">
            <h1 class="page-title">Ficha do Veículo</h1>
            
            <!-- Visão geral do veículo com Última Manutenção -->
            <section class="vehicle-overview">
                <div class="vehicle-info">
                    <h2 class="vehicle-title"><?php echo htmlspecialchars($veiculo['nome_modelo']) ?></h2>
                    <p><strong>Placa: </strong><?php echo htmlspecialchars($veiculo['placa']) ?></p>
                    <p><strong>Marca: </strong><?php echo htmlspecialchars($veiculo['nome_marca']) ?></p>
                    <p><strong>Modelo: </strong><?php echo htmlspecialchars($veiculo['nome_modelo']) ?></p>
                    <p><strong>Tipo: </strong><?php echo htmlspecialchars($veiculo['nome_tipo_veiculo']) ?></p>
                    <p><strong>Cor: </strong><?php echo htmlspecialchars($veiculo['nome_cor']) ?></p>
                </div>
                <div class="last-maintenance">
                    <h3>Última Manutenção</h3>
                    <p><strong>Data: </strong>
                        <?php
                            $dataOriginal = $servicos[0]['data'];
                            $dataFormatada = date('d/m/Y', strtotime($dataOriginal));
                            echo htmlspecialchars($dataFormatada);
                        ?>
                    </p>
                    <p><strong>Defeito Reclamado: </strong><?php echo htmlspecialchars($servicos[0]['defeito']); ?></p>
                </div>
            </section>

            <!-- Histórico de manutenção -->
            <section class="vehicle-history">
                <h3>Histórico de Manutenções</h3>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Serviço</th>
                            <th>Detalhes</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($servicos as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($item['data']))); ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['descricao']); ?></td>
                                <td><?php echo "R$ " . htmlspecialchars($item['valor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Histórico de peças -->
            <section class="vehicle-parts-history">
                <h3>Histórico de Peças</h3>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Peça</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtos as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($item['data']))); ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                                <td><?php echo "R$ " . htmlspecialchars($item['valor']); ?></td>
                                <td><?php echo "R$ " . htmlspecialchars($item['quantidade'] * $item['valor']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Ações -->
            <section class="actions">
                <button id="botaoImprimir" class="btn-secondary"><i class="fa-solid fa-file"></i> Baixar Relatório</button>
            </section>
        </div>
    </div>
</main>


<?php include '../includes/imprimir.php'; ?>

</body>
</html>
<?php endif; ?>