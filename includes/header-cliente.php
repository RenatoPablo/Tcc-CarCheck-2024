<?php


// Verifica se a variável de sessão 'permissao' existe e define a URL com base no valor
if (isset($_SESSION['permissao'])) {
    $permissao = $_SESSION['permissao'];
    
    // Define a URL com base na permissão
    if ($permissao == 1) {
        $url = "../pages/home-cliente.php";
    } elseif ($permissao == 2 || $permissao == 3) {
        $url = "../pages/home-funci.php";
    } 
}
?>
<!-- header.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "CarCheck"; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="sidebar">
            
            <a href="../pages/veiculoscliente.php">Veículos</a>
            <a href="../pages/servicos-cliente.php">Serviços</a>
            <a href="../pages/agendamento.php">Agendamentos</a>
            <a href="../pages/associados.php">Associados</a>
            <a href="../config/sair.php">Sair</a>
        </div>
        
        <div class="container-header">
        <button class="buttonheader" onclick="window.history.back();">
                <svg height="16" width="16" 
                xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024"><path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z">
                </path></svg>
                <span>Voltar</span>
            </button>
            <div class="logoCarcheck"><img src="../image/logoNovo.jpg" alt="Logo CarCheck"></div>
            <h1>CarCheck</h1>
        </div>
    
        <div class="icons">
              
            <a href="<?php echo $url; ?>"><i class="fa-solid fa-house-chimney fa-2xl casa" style="color: #ffffff;"></i></a>
            
            <a href="../pages/perfil.php"><i class="fa-solid fa-user fa-2xl" style="color: #ffffff;"></i></a>
        </div>
        
        <input type="checkbox" id="checkbox" onclick="toggleSidebar()">
        <label for="checkbox" class="toggle">
            <div class="bar bar--top"></div>
            <div class="bar bar--middle"></div>
            <div class="bar bar--bottom"></div>
        </label>
    </header>

    <!-- Scripts necessários -->
    <script src="../js/script.js"></script>
    <script src="../js/popup-not.js"></script>
</body>
</html>
