<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

    require '../config/config.php';

    $sql = "SELECT 
                m.time_saida AS data,
                m.defeito,
                m.valor_total,
                m.codigo,

                mo.nome_modelo,
                c.nome_cor
            FROM
                manutencoes m
            LEFT JOIN
                veiculos v ON v.id_veiculo = m.fk_id_veiculo
            LEFT JOIN
                modelos mo ON mo.id_modelo = v.fk_id_modelo
            LEFT JOIN
                cores c ON c.id_cor = v.fk_id_cor
            LEFT JOIN
                pessoas p ON p.id_pessoa = v.fk_id_pessoa
            WHERE 
                p.id_pessoa = :id AND m.faturamento = 1
            ORDER BY
                m.time_saida DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_SESSION['id_pessoa']]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //var_dump($resultado);
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços</title>
    <link rel="stylesheet" href="../css/servicos-cliente.css">
    <link rel="stylesheet" href="../css/forma-pagamento.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header-cliente.php'; ?>

<main class="servicos-cliente">
    <div class="container">
        <!-- Introdução da Página -->
        <section class="intro-servicos">
            <h1>Gerenciamento de Serviços</h1>
            <p>Escolha, acompanhe e solicite serviços de forma rápida e prática. Aqui você encontra tudo para cuidar do seu veículo.</p>
        </section>

        <!-- Seção de Serviços -->
        <section class="servicos-disponiveis">
            <div class="servicos-cards">
                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-oil-can"></i>
                    </div>
                    <div class="card-content">
                        <h3>Troca de Óleo</h3>
                        <p>Mantenha o motor do seu veículo em perfeito funcionamento com a troca de óleo.</p>
                        <a href="https://wa.me/5518997610162?text=Olá, gostaria de solicitar o serviço de Troca de Óleo!" class="btn-primary">Solicitar no WhatsApp</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-car-crash"></i>
                    </div>
                    <div class="card-content">
                        <h3>Alinhamento e Balanceamento</h3>
                        <p>Garanta estabilidade e segurança em cada viagem.</p>
                        <a href="https://wa.me/5518997610162?text=Olá, gostaria de solicitar o serviço de Alinhamento e Balanceamento!" class="btn-primary">Solicitar no WhatsApp</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <i class="fa-solid fa-tools"></i>
                    </div>
                    <div class="card-content">
                        <h3>Revisão Completa</h3>
                        <p>Uma análise detalhada para garantir que tudo esteja funcionando perfeitamente.</p>
                        <a href="https://wa.me/5518997610162?text=Olá, gostaria de solicitar o serviço de Revisão Completa!" class="btn-primary">Solicitar no WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Histórico de Serviços -->
        <section class="historico-servicos">
            <h2>Histórico de Manutenções</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Código</th>
                            <th>Problema reclamado</th>
                            <th>Carro</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php foreach($resultado as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($item['data']))); ?></td>
                                <td><?php echo htmlspecialchars($item['codigo'])?></td>
                                <td><?php echo htmlspecialchars($item['defeito'])?></td>
                                <td><?php echo htmlspecialchars($item['nome_modelo']) . " ". htmlspecialchars($item['nome_cor'])?></td> 
                                <td><?php echo "R$ " . htmlspecialchars($item['valor_total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Suporte ao Cliente -->
        <section class="suporte-cliente">
            <h2>Precisa de Ajuda?</h2>
            <p>Entre em contato com nosso suporte para esclarecer dúvidas ou solicitar serviços especiais.</p>
            <a href="https://wa.me/5518997610162?text=Olá, gostaria de mais informações sobre os serviços disponíveis!" class="btn-support">
                <i class="fa-solid fa-headset"></i> Contatar Suporte
            </a>
        </section>
    </div>
</main>

</body>
</html>
<?php endif; ?>