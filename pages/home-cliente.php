<?php
    session_start();
    // print_r($_SESSION);
    if(!isset($_SESSION) OR $_SESSION['logado'] != true):
		header("location: ../config/sair.php");		
	else:
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/home-cliente.css">
    <link rel="stylesheet" href="../css/card-itens.css">
    <title>CarCheck</title>
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/popup-not.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        body {
            padding-top: 115px;
            overflow-x: hidden; /* Evita rolagem horizontal */
        }
    </style>
</head>
<body>
<?php
include '../includes/header-cliente.php';
?>
    <div class="containercliente">
        <!--div que contem os itens do acesso rapido-->
        <div class="container-acesso-rapido"> 
            <h2 class="textoh2">ACESSO RÁPIDO</h2>
            <div class="container-acesso">
                
                <a href="../pages/veiculoscliente.php">
                    <div class="card">
                        <img src="../image/carro.png" alt="Veículos" class="img-card-especifica">
                        <p class="heading">Veículos</p>
                    </div>
                </a>
                <a href="../pages/servicos-cliente.php">
                    <div class="card">
                        <img src="../image/iconeServiços.png" alt="Serviços" class="img-card-acesso">
                        <p class="heading">Serviços</p>
                    </div>
                </a>

                <a href="../pages/agendamento.php">
                    <div class="card">
                        <img src="../image/iconeAgendamento.png" alt="Agendamento" class="img-card-acesso">
                        <p class="heading">Agendamentos</p>
                    </div>
                </a>

                <a href="../pages/associados.php">
                    <div class="card">
                        <img src="../image/iconeAssociados.png" alt="Associados" class="img-card-acesso">
                        <p class="heading">Associados</p>
                    </div>
                </a>

                
            </div>
        </div>
    </div>
    <script src="../js/script.js"></script>
    <script src="../js/popup-not.js"></script>
</body>
</html>
<?php endif; ?>