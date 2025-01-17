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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/popup-not.css">
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/forma-pagamento.css">
    <link rel="stylesheet" href="../css/barra-pesquisa.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Forma Pagamento</title>
</head>
<?php
include '../includes/header.php';
?>
<body>

<div class="container mt-4">
    <h2 class="text-center">Gerenciamento de Formas de Pagamentos</h2>

    <!-- Botão para abrir o modal -->
    <button id="btnAbrirModalCadastro" class="btn-cadastrar">Cadastrar Forma de Pagamento</button>

    <!-- Campo de busca -->
    <div class="search-bar mb-3">
        <input type="text" id="inputBusca" placeholder="Buscar item..." class="form-control">
        <span class="icon-search"><i class="fa fa-search"></i></span>
    </div>

    <!-- Modal para cadastro de item -->
    <div id="modalCadastro" class="modal-forma-pagamento">
        <div class="modal-content-forma">
            <span class="modal-close">&times;</span>
            <h2>Cadastrar Forma de Pagamento</h2>
            
            <!-- Formulário de cadastro -->
            <form id="formCadastro" method="POST" action="../config/forma-pagamento/create.php">
                <div class="mb-3">
                    <label for="inputNome" class="form-label">Nome:</label>
                    <input type="text" id="inputNome" class="form-control" name="nomeItem" required>
                </div>

                <button type="submit" class="btn btn-success">Salvar</button>
                </form>
        </div>
    </div>

    <!-- Lista de itens -->
    <div class="mt-4">
        <h2 class="text-center">Lista de Formas de pagamentos</h2>
        <table class="table-list">
            <thead>
                <tr>
                    <th>Forma pagamento</th>
                    <th>Ações</th>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloAcao">Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="mensagemModalAcao"></p>

                <!-- Formulário para update -->
                <form id="formUpdate" class="d-none" method="POST" action="../config/forma-pagamento/update.php">
                    <!-- Campo oculto para armazenar o ID do item -->
                    <input type="hidden" id="inputIdItem" name="id_item">
                    
                    <!-- Campo de texto para o nome do item -->
                    <div class="input-container-forma mb-3">
                        <label for="inputUpdateNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="inputUpdateNome" name="nomeItem" value="">
                    </div>

                    <!-- Botão para salvar as alterações -->
                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>

                <!-- Formulário para delete -->
                <form action="../config/forma-pagamento/delete.php" method="POST">
                    <input type="hidden" id="inputDeleteIdItem" name="id_item">
                    <input type="hidden" id="inputDeleteNome" name="nomeItem" value="">
                    <!-- Botão para confirmar exclusão -->
                    <button id="btnConfirmarDelete" class="btn btn-danger d-none">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Carrega os itens ao abrir a página -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        carregarItens(); // Chama a função quando o DOM estiver carregado
    });
</script>

<?php include '../includes/popup-padrao.php'; ?>

<!-- Scripts carregados no final para melhor performance -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="../js/grid/read/read-forma-pagamento.js"></script>
<script src="../js/grid/modal.js"></script>

</body>
</html>

<?php endif; ?>
