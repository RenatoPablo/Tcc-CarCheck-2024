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
    <link rel="stylesheet" href="../css/cadastrar-veiculo.css">
    <link rel="stylesheet" href="../css/modal.css">
    <link rel="stylesheet" href="../css/barra-pesquisa.css">
    <link rel="stylesheet" href="../css/padraoformularios.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Veículos</title>
</head>
<body>

<?php
include '../includes/header.php';
?>
<div class="container mt-4">
    <h2 class="text-center">Gerenciamento de Veículos</h2>

    <!-- Botão para abrir o modal -->
    <button id="btnAbrirModalCadastro" class="btn-cadastrar">Cadastrar Veículo</button>

    <!-- Campo de busca -->
    <div class="search-bar mb-3">
        <input type="text" id="inputBusca" placeholder="Buscar item..." class="form-control">
        <span class="icon-search"><i class="fa fa-search"></i></span>
    </div>

    <!-- Modal para cadastro de item -->
    <div id="modalCadastro" class="modal-forma-pagamento">
        <div class="modal-content-forma">
            <span class="modal-close">&times;</span>
            <h2>Cadastrar Novo Veiculo</h2>
            
            <!-- Formulário de cadastro -->
            <form action="../config/crud-veiculo/create.php" method="post"  enctype="multipart/form-data">

            <div class="mb-3">
                <div class="prop">
                    <label for="prop" class="form-label">Proprietário</label>
                    <input id="prop" type="text" name="proprietario" class="form-control" class="input" onkeyup="buscarProprietarios()" autocomplete="off">
                    <ul id="sugestoes" class="suggestions"></ul>
                </div>
            </div>
                <div class="mb-3">
                    <label for="placa" class="form-label">Placa</label>
                    <input id="placa" type="text" name="placa" class="form-control" class="input" oninput="mascaraPlacaVeiculo(this)">
                </div>
                
                <div class="mb-3">
                    <label for="modelo" class="form-label">Modelo</label>
                    <input id="modelo" name="modelo" type="text" class="form-control" class="input">
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo Veículo</label>
                    <input id="tipo" name="tipo" type="text" class="form-control" class="input">
                </div>
                <div class="mb-3">
                    <label for="cor" class="form-label">Cor</label>
                    <input id="cor" name="cor" type="text" class="form-control" class="input">
                </div>
                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input id="marca" name="marca" type="text" class="form-control" class="input">
                </div>
                <button type="submit" class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>

    <!-- Lista de itens -->
    <div class="mt-4">
        <h2 class="text-center">Lista de Veículos</h2>
        <table class="table-list">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Placa</th>
                    <th>Nome Proprietário</th>
                    <th>Status</th>
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

                            
                
                           

                <div id="modalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger d-none" id="confirmDeleteBtn">Confirmar Exclusão</button>
                
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

<script src="../js/grid/read/read-veiculo.js"></script>
<script src="../js/grid/modal.js"></script>

<script src="../js/buscarProprietario.js"></script>
<script src="../js/mascaras.js"></script>

</body>
</html>

<?php endif; ?>
