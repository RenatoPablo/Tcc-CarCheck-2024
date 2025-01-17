<?php
session_start();
?>
<?php
include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Não Encontrada</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding-top: 115px;
            overflow-x: hidden; 
        }
        .error-container {
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            color: #ff0000;
        }
        p {
            font-size: 1.2rem;
        }
        .errovoltar {
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0D3587;
            border-radius: 5px;
            color: white;
        }
        .errovoltar:hover {
            background-color: #0a2c6b;
        }
        .imgcarro{
            width: 500px;
            height: auto;
        }
    </style>
</head>


<body>
    <div class="error-container">
        <h1>404 - Página Não Encontrada</h1>
        <img src="\carcheck\image\young-mechanic-fix-blue-car-opening-hood_7496-116.png" class="imgcarro">
        <p>Ops! A página que você está procurando não existe.</p>

        <!-- Link "Voltar para a tela inicial" com redirecionamento dinâmico -->
        <a href="<?php 
            // Verifica a permissão do usuário e define o link de redirecionamento
            if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
                if ($_SESSION['permissao'] == 1) {
                    echo '/carcheck/pages/home-cliente.php';
                } else {
                    echo '/carcheck/pages/home-funci.php';
                }
            } else {
                // Redireciona para a página de login se o usuário não estiver logado
                echo '/carcheck/pages/login.php';
            }
        ?>" class="errovoltar">Voltar para a tela inicial</a>
    </div>
</body>
</html>
