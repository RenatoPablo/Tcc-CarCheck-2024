<?php
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
		header("location: ../config/sair.php");		
	else:
    $permissao = $_SESSION['permissao'];

    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emitir ordem de serviço</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/popup-not.css">
    <link rel="stylesheet" href="../css/emitir-ordem.css">~
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/padraoformularios.css">
    <link rel="stylesheet" href="../css/barra-pesquisa.css">

</head>
<body>

<?php 
include '../includes/header.php'; 
include '../includes/busca-forma-pagamento.php';
?>

<main>
<h2 class="titulo-formulario">Emitir Ordem de Serviço</h2>
    <form id="finalForm" action="../config/adicionar-manutencao.php" method="POST">
        <div class="form-card dados-manutencao">
            <h3>Dados da Manutenção</h3>
            <div class="input-container">
                <label class="label-campos" for="time-final">Hora de saída:</label>
                <input type="datetime" name="time-final" id="time-final" class="input">
            </div>
            <div class="input-container">
                <label class="label-campos" for="km">KM:</label>
                <input type="number" name="km" id="km" class="input">
            </div>
            <div class="input-container">
                <label class="label-campos" for="defeito">Defeito observado:</label>
                <input type="text" name="defeito" id="defeito" class="input">
            </div>
            <div class="input-container">
                <label class="label-campos" for="placa">Placa:</label>
                <input type="text" name="placa" id="placa" oninput="mascaraPlacaVeiculo(this)" class="input">
            </div>
            <div class="input-container">
                <label class="label-campos" for="veiculo">Carro:</label>
                <input type="text" name="veiculo" id="veiculo" class="input">
            </div>
            <div class="input-container">
                <label class="label-campos" for="prop">Proprietário do veículo:</label>
                <input type="text" name="proprietario" id="prop" class="input" onkeyup="buscarProprietarios()">
                <ul id="sugestoes" class="suggestions"></ul>
            </div>

            <h3>Serviços e Peças</h3>
            <div class="input-container">
                <label for="servico">Adicionar serviços</label>
                <input placeholder="Buscar item do estoque" type="text" id="estoqueServico" name="servico" class="input" onkeyup="buscarEstoqueServico()"/>
                <ul id="sugestoesServico" class="suggestions"></ul>
                <button type="button" id="addItemBtnServico" onclick="adicionarItemServico()">Adicionar à lista</button>
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
                <label for="valorTotal">Valor total da nota:</label>
                <input type="text" name="valorTotal" id="valorTotal" class="input" readonly oninput="mascaraValor()">
            </div>

            <input type="hidden" id="hiddenItemListServico" name="itemListServico">
            <input type="hidden" id="hiddenItemListProduto" name="itemListProduto">

            <button type="submit" class="btn-submit">Finalizar nota</button>
        </div>
    </form>
</main>

<script src="../js/adicionar-lista.js"></script>    
<script src="../js/buscarProprietario.js"></script>
<script src="../js/buscar-veiculo.js"></script>
<script src="../js/mascaras.js"></script>
<script>
    function definirHoraAtualTime() {
        const agora = new Date();
        const horas = agora.getHours().toString().padStart(2, '0');
        const minutos = agora.getMinutes().toString().padStart(2, '0');
        document.getElementById('time-final').value = `${horas}:${minutos}`;
    }
    window.onload = definirHoraAtualTime;
</script>
</body>
</html>
<?php endif; ?>

