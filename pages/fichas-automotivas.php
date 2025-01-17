<?php 
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");        
    else:
    $permissao = $_SESSION['permissao'];

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/barra-pesquisa.css">
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/forma-pagamento.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>Fichas Automotivas</title>
</head>
<body>
<?php
include '../includes/header.php';
?>

<div class="container mt-4">
<h2 class="text-center">Painel de Registros Automotivos</h2>

    <!-- Campo de busca -->
    <div class="search-bar mb-3">
        <input type="text" id="inputBusca" placeholder="Buscar item..." class="form-control" oninput="mascaraPlacaVeiculo(this)">
        <span class="icon-search"><i class="fa fa-search"></i></span>
    </div>

    <!-- Lista de itens -->
    <div class="mt-4">
        <h2 class="text-center">Fichas de Manutenção</h2>
        <table class="table-list">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Placa</th>
                    <th>Cor</th>
                    <th>Visualizar</th>                  
                </tr>
            </thead>
            <tbody id="gridResultadoBusca">
                <!-- Os resultados da grid aparecerão aqui -->
            </tbody>
        </table>
    </div>
</div>



<script src="../js/grid/read/read-ficha.js"></script>
<script src="../js/mascaras.js"></script>
</body>
</html>

<?php endif; ?>