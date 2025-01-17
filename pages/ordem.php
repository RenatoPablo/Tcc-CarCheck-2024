<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

    
?>

<?php
// Conectar ao banco de dados
require '../config/config.php'; // Substitua pelo caminho correto do seu arquivo de conexão

// Consultar as formas de pagamento
$sql = "SELECT * FROM formas_pagamento";
$stmt = $pdo->query($sql);

// Armazenar os resultados em um array
$formasPagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/popup-not.css">
    <link rel="stylesheet" href="../css/cadastro-servico.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/forma-pagamento.css">
    <link rel="stylesheet" href="../css/barra-pesquisa.css">
    <link rel="stylesheet" href="../css/emitir-ordem.css">
    <link rel="stylesheet" href="../css/padraoformularios.css">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Ordem de Serviço</title>
</head>
<body>

<?php
include '../includes/header.php';
?>
<div class="container mt-4">
    <h2 class="text-center">Gerenciamento de Ordens de Serviços</h2>

    <!-- Botão para abrir o modal -->
    <button id="btnAbrirModalCadastro" class="btn-cadastrar">Emitir Ordem de Serviço</button>

    <!-- Campo de busca -->
    <div class="search-bar mb-3">
        <input type="text" id="inputBusca" placeholder="Buscar item..." class="form-control">
        <span class="icon-search"><i class="fa fa-search"></i></span>
    </div>

    <!-- Modal para cadastro de item -->
    <div id="modalCadastro" class="modal-forma-pagamento">
        <div class="modal-content-forma">
            <span class="modal-close">&times;</span>
            <h2>Emitir Ordem de Serviço</h2>
            
            <form id="finalForm" action="../config/crud-nota/create.php" method="POST">
            <div class="form-card dados-manutencao">
            <h3 class="subtitulo-ordem">Manutenção</h3>
                <div class="input-container">
                    <label class="label-campos" for="time-final">Hora de saída:</label>
                    <input type="datetime" name="time-final" id="time-final" class="input">
                </div>
                <div class="input-container">
                    <label class="label-campos" for="km">KM:</label>
                    <input type="text" name="km" id="km" class="input">
                </div>
                <div class="input-container">
                    <label class="label-campos" for="defeito">Defeito observado:</label>
                    <input type="text" name="defeito" id="defeito" class="input">
                </div>
                <div class="input-container">
                    <label class="label-campos" for="placa">Placa:</label>
                    <input type="text" name="placa" id="placa" oninput="mascaraPlacaVeiculo(this)" class="input">
                </div>
                

                <br>

                <h3 class="subtitulo-ordem">Serviços e Peças</h3>
                <div class="input-container">
                    <label for="servico">Adicionar serviços</label>

                    <input placeholder="Buscar item do estoque" type="text" id="estoqueServico" name="servico" class="input" onkeyup="buscarEstoqueServico()"/>

                    <ul id="sugestoesServico" class="suggestions"></ul>

                    <button id="addItemBtnServico" onclick="adicionarItemServico(event)">Adicionar à lista</button>

                    <ul id="itemListServico" class="ul-temporaria"></ul>
                </div>

                <div class="input-container">
                    <label for="produto">Adicionar peças</label>

                    <input placeholder="Buscar item do estoque" type="text" id="estoqueProduto" name="produto" class="input" onkeyup="buscarEstoqueProduto()"/>

                    <ul id="sugestoesProduto" class="suggestions"></ul>

                    <input type="number" id="quantidadeProduto" class="input" placeholder="Quantidade" style="display: none;"/>

                    <button id="addItemBtnProduto" onclick="adicionarItemProduto()">Adicionar à lista</button>

                    <ul id="itemListProduto" class="ul-temporaria"></ul>
                </div>

                

                <div class="input-container">
                    <label for="valorTotal">Valor total da nota:</label>
                    <input type="text" name="valorTotal" id="valorTotal" class="input" readonly oninput="mascaraValor()">
                </div>

                

                <input type="hidden" id="hiddenItemListServico" name="itemListServico">
                <input type="hidden" id="hiddenItemListProduto" name="itemListProduto">

            <button type="submit" class="btn-submit">Finalizar nota</button>
                </div>
            </form>
            
            
        </div>
    </div>

    <!-- Lista de itens -->
    <div class="mt-4">
        <h2 class="text-center">Ordens de Serviços</h2>
        <table class="table-list">
            <thead>
                <tr>
                    <th class="col-data">Data</th>
                    <th>Código</th>
                    <th class="col-data">Proprietário</th>
                    <th>Veículo</th>
                    <th class="col-data">Placa</th>
                    <th class="col-data">Valor Total</th>
                    <th>Faturamento</th>
                    <th>Pagamentos</th>
                    <th>Ações</th>
                    <th>Visualizar</th>
                </tr>
            </thead>
            <tbody id="gridResultadoBusca">
                <!-- Os resultados da grid aparecerão aqui -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Update e Delete -->
<div class="modal fade" id="modalAcao" tabindex="-1" aria-labelledby="modalTituloAcao" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloAcao">Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="mensagemModalAcao"></p>

                            
                
                           

                <div id="modalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger d-none" id="confirmDeleteBtn">Confirmar Exclusão</button>
                
            </div>
        </div>
    </div>
</div>


<!-- Modal para Processar Pagamento -->
<div id="modalPagamento" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="fecharModalPagamento()">&times;</span>
        <h2>Processar Pagamento</h2>
        <form id="formProcessarPagamento" method="post" action="../config/crud-pagamento/create.php">
            <!-- ID da manutenção será exibido aqui -->
            <input type="hidden" id="inputIdManutencao" name="id_manutencao">
            
            <div class="input-container">
                <label for="valorPagamento">Valor do Pagamento:</label>
                <input type="number" id="valorPagamento" name="valor_pagamento" step="0.01" required>
            </div>

            <div class="input-container">
                <label for="dataPagamento">Data do Pagamento:</label>
                <input type="date" id="dataPagamento" name="data_pagamento" required>
            </div>

            <div class="input-container">
                    <label for="formaPagamento">Forma de Pagamento:</label>
                    <select name="formaPagamento" id="formaPagamento" class="formaPagamento">

                    <?php foreach ($formasPagamento as $forma): ?>
                        <option value="<?= $forma['id_forma_pagamento']; ?>">
                            <?= htmlspecialchars($forma['tipo_pagamento']); ?>
                        </option>
                    <?php endforeach; ?>

                    </select>
                </div>


                <div class="input-container">
                    <label for="parcela">Parcela:</label>
                    <input type="number" name="parcela" id="parcela" class="input" min="1">
                    <div id="valorParcela" class="parcela-valor"></div> 
                </div>

            <button type="submit">Processar Pagamento</button>
        </form>
    </div>
</div>

<!-- Modal de Gerenciamento de Pagamento -->
<div id="modalGerenciamentoPagamento" class="modal" data-id-manutencao="">
    <div class="modal-content">
        <span class="close" onclick="fecharModalGerenciamentoPagamento()">&times;</span>
        <h2 id="tituloModalGerenciamentoPagamento">Gerenciamento de Pagamento</h2>

        <!-- Tabela para exibir os pagamentos -->
        <table id="tabelaPagamentos" class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Valor Parcela</th>
                    <th>Data Parcela</th>
                    <th>Numero da Parcela</th>
                    <th>Situação</th>
                    <th>Editar</th>
                    <th>Pagar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Os dados serão preenchidos dinamicamente aqui -->
            </tbody>
        </table>
    </div>
</div>

<div id="modalEditarPagamento" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModalEditar()">&times;</span>
        <h2>Editar Pagamento</h2>

        <form action="../config/crud-pagamento/update.php" method="POST">
            <input type="hidden" id="editarIdPagamento" name="id_pagamento">

            <div class="mb-3">
                <label for="editarCodigo" class="form-label">Código:</label>
                <input type="text" id="editarCodigo" name="codigo" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="editarValorParcela" class="form-label">Valor Parcela:</label>
                <input type="text" id="editarValorParcela" name="valor_parcela" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="editarNumParcela" class="form-label">Número Parcela:</label>
                <input type="text" id="editarNumParcela" name="num_parcela" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="editarDataParcela" class="form-label">Data Parcela:</label>
                <input type="date" id="editarDataParcela" name="data_parcela" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</div>



<!-- Carrega os itens ao abrir a página -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        carregarItens(); // Chama a função quando o DOM estiver carregado
    });
</script>


<!-- Scripts carregados no final para melhor performance -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="../js/adicionar-lista.js"></script>

<script src="../js/grid/modal.js"></script>

<script src="../js/buscarProprietario.js"></script>
<script src="../js/mascaras.js"></script>

<!-- <script src="../js/adicionar-lista.js"></script>     -->
<script src="../js/buscarProprietario.js"></script>
<script src="../js/buscar-veiculo.js"></script>



<script>
    function definirHoraAtualTime() {
        const agora = new Date();
        const horas = agora.getHours().toString().padStart(2, '0');
        const minutos = agora.getMinutes().toString().padStart(2, '0');
        document.getElementById('time-final').value = `${horas}:${minutos}`;
    }

    // Usando o evento `DOMContentLoaded` para garantir que a função só execute após o carregamento completo
    document.addEventListener("DOMContentLoaded", definirHoraAtualTime);

    document.getElementById('parcela').addEventListener('input', function () {
    const parcelas = parseInt(this.value); // Número de parcelas
    // Obtém o valor do campo com ID 'valorPagamento'
    const inputValorPagamento = document.getElementById('valorPagamento');

    // Converte o valor do input para número (considerando que o valor será numérico)
    const valorTotal = parseFloat(inputValorPagamento.value) || 0;
    
    if (parcelas > 0) {
        const valorPorParcela = (valorTotal / parcelas).toFixed(2); // Calcula o valor da parcela
        document.getElementById('valorParcela').textContent = `Valor por parcela: R$ ${valorPorParcela}`;
    } else {
        document.getElementById('valorParcela').textContent = ''; // Limpa a mensagem se o valor for inválido
    }
});

</script>
<?php include '../includes/popup-padrao.php'; ?>
<script src="../js/grid/read/read-ordem.js"></script>
</body>
</html>

<?php endif; ?>


