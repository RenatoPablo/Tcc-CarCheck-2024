<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

        require '../config/config.php';

    $placa = htmlspecialchars($_GET['placa']);

    //var_dump($placa);

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
                v.placa = :placa";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':placa' => $placa]);
        $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

        $idVeiculo = $veiculo['id_veiculo'];
try {
            $sqlServicos = "
        SELECT 
            s.id_servico_produto,
            s.nome_servico_produto AS nome,
            s.descricao,
            s.valor_servico_produto AS valor,
            m.codigo AS manutencao_codigo,
            m.time_saida AS data
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
    $sqlProdutos = "
        SELECT 
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
    <title>Histórico do Veículo</title>
    <link rel="stylesheet" href="../css/detalhes-ficha.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div id="conteudo">
        <div class="container">
            <h1 class="titulo">Informações do Veículo</h1>

            <!-- Ficha do veículo -->
            <div class="ficha-veiculo">
                <div class="campo">
                    <label>Modelo:</label>
                    <span><?php echo $veiculo['nome_modelo']?></span>
                </div>
                <div class="campo">
                    <label>Placa:</label>
                    <span><?php echo $veiculo['placa']?></span>
                </div>
                <div class="campo">
                    <label>Marca:</label>
                    <span><?php echo $veiculo['nome_marca']?></span>
                </div>
                <div class="campo">
                    <label>Cor:</label>
                    <span><?php echo $veiculo['nome_cor']?></span>
                </div>
                <div class="campo">
                    <label>Tipo Veículo:</label>
                    <span><?php echo $veiculo['nome_tipo_veiculo']?></span>
                </div>
            </div>

            <!-- Tabelas de histórico -->
            <div class="tabelas-historico">
                <!-- Histórico de serviços -->
                <div class="tabela-container">
                    <h2>Histórico de Serviços</h2>
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Serviço</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($servicos as $item): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($item['data'])); ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['descricao']); ?></td>
                                <td>R$ <?php echo htmlspecialchars($item['valor']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Histórico de peças -->
                <div class="tabela-container">
                    <h2>Histórico de Peças</h2>
                    <table class="tabela">
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
                                <td><?php echo date('d/m/Y', strtotime($item['data'])); ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                                <td>R$ <?php echo htmlspecialchars($item['valor']); ?></td>
                                <td>R$ <?php echo htmlspecialchars($item['quantidade'] * $item['valor']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <button class="btn-imprimir" id="botaoImprimir">Imprimir</button>
    </div>

    <?php include '../includes/imprimir.php'; ?>
</body>
</html>


<?php endif; ?>