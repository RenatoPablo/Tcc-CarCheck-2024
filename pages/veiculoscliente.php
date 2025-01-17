<?php
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
        header("location: ../config/sair.php");		
    else:
    require '../config/config.php';
    require '../config/crud-veiculo/read-cliente.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/padrao-grid.css">
    <link rel="stylesheet" href="../css/emitir-ordem.css">
    <title>Meus veículos</title>

    <style>
        body{
            padding-top: 100px;
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<?php
include '../includes/header-cliente.php';
?>
<body>
<div class="container mt-4">
    <!-- Lista de itens -->
    <div class="mt-4">
        <h2 class="text-center">Lista de Veículos</h2>
        <table class="table-list">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Placa</th>
                    <th>Cor</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Ficha Veiculo</th>
                </tr>
            </thead>
            <tbody id="gridResultadoBusca">
                
                    <?php
                        foreach($veiculos as $itens) :
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($itens['nome_modelo'])?></td>
                        <td><?php echo htmlspecialchars($itens['placa'])?></td>
                        <td><?php echo htmlspecialchars($itens['nome_cor'])?></td>
                        <td><?php echo htmlspecialchars($itens['nome_tipo_veiculo'])?></td>
                        <td><?php echo htmlspecialchars($itens['nome_marca'])?></td>
                        <td>
                            <button class="btn-visualizar" type="button" onclick="redirecionarParaFicha(<?php echo $itens['id_veiculo']; ?>)">Ficha</button>
                        </td>

                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function redirecionarParaFicha(idVeiculo) {
        // Redireciona para a página de destino com o id do veículo como parâmetro
        window.location.href = `../pages/ficha-veiculo.php?id_veiculo=${idVeiculo}`;
    }
</script>


</body>
</html>
<?php endif; ?>