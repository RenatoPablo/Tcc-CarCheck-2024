<?php
    session_start();
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
		header("location: ../config/sair.php");		
	else:
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/home-funci.css"> <!-- CSS da Sidebar -->
    <link rel="stylesheet" href="../css/card-itens.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/popup-not.css">
    
    <title>Acesso Rápido</title>
</head>
<body>
<?php
include '../includes/header.php';
?>
    <div class="div-inicial">
        <h2>
            <?php
                if (isset($_SESSION['nomeUsuario'])) {
                    echo "Olá, " . htmlspecialchars($_SESSION['nomeUsuario']);
                }
            ?>
        </h2>
        <p class="div-inicial-p">O que você deseja fazer? Selecione uma das opções:</p>
        
        <div class="container-acesso">
            <!-- Cartões de acesso rápido -->
            <a href="../pages/cliente.php">
                <div class="card">
                <img src="../image/iconeCadastrarCliente.png" alt="Cadastrar Cliente" class="img-card">
                    <p class="heading">Clientes</p>
                </div>
            </a>
            <a href="../pages/veiculo.php">
                <div class="card">
                    <img src="../image/carro.png" alt="icone de carro" class="img-card-especifica">
                    <p class="heading">Veículos</p>
                </div>
            </a>
            <a href="../pages/fichas-automotivas.php">
                <div class="card">
                    <img src="../image/iconeConsultarOrdemServiço.png" alt="Consultar Fichas de Clientes" class="img-card">
                    <p class="heading">Registros Automotivos</p>
                </div>
            </a>
            <a href="../pages/servico.php">
                <div class="card">
                    <img src="../image/iconeCadastrarServiço.png" alt="Cadastrar Serviço ou Produto" class="img-card">
                    <p class="heading">Serviços</p>
                </div>
            </a>
            <a href="../pages/produto.php">
                <div class="card">
                <img src="../image/iconeGerenciarEstoque.png" alt="Gerenciar Produtos" class="img-card">
                    <p class="heading">Produtos</p>
                </div>
            </a>
            <a href="../pages/ordem.php">
                <div class="card">
                    <img src="../image/iconeEmetirOrdemServiço.png" alt="icone emetir ordem de serviço" class="img-card">
                    <p class="heading">Ordem de Serviço</p>
                </div>
            </a>
        </div>
    </div>
    

    
</body>
</html>
<?php endif; ?>