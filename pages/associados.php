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
    <title>Oficinas Associadas</title>
    <link rel="stylesheet" href="../css/associados.css">
</head>
<?php
include '../includes/header-cliente.php';
?>
<body>

    <div class="associados-header">
        <h1 class="associados-titulo">Oficinas Associadas</h1>
        <p class="associados-descricao">Encontre uma oficina parceira e agende seu serviço diretamente pelo sistema.</p>
        <br>
        <br>
    </div>

    <main class="container-associados">
        <div class="associado-card">
            <h2 class="associado-nome">Oficina Auto Prime</h2>
            <p class="associado-info"><strong>Endereço:</strong> Rua das Flores, 123 - Centro</p>
            <p class="associado-info"><strong>Telefone:</strong> (11) 98765-4321</p>
            <a href="agendamento_servico.html" class="btn-agendar-servico">Agendar Serviço</a>
        </div>

        <div class="associado-card">
            <h2 class="associado-nome">ReparAuto Mecânica</h2>
            <p class="associado-info"><strong>Endereço:</strong> Avenida Principal, 456 - Bairro Industrial</p>
            <p class="associado-info"><strong>Telefone:</strong> (21) 99876-5432</p>
            <a href="agendamento_servico.html" class="btn-agendar-servico">Agendar Serviço</a>
        </div>

        <div class="associado-card">
            <h2 class="associado-nome">Mecânica do João</h2>
            <p class="associado-info"><strong>Endereço:</strong> Rua do Comércio, 789 - Bairro Alto</p>
            <p class="associado-info"><strong>Telefone:</strong> (31) 91234-5678</p>
            <a href="agendamento_servico.html" class="btn-agendar-servico">Agendar Serviço</a>
        </div>

        <!-- Adicione mais oficinas conforme necessário -->
    </main>

</body>
</html>
<?php endif; ?>