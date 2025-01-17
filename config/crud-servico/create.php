<?php 
    session_start();
    if (!isset($_SESSION) OR $_SESSION['logado'] != true) {
        header("location: ../config/sair.php");
        exit();	
    }

require '../config.php';
require '../../function/funcoes_servico.php';

// var_dump($_POST);
// exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    
        if (!empty($_POST['nome']) &&
            !empty($_POST['descricao']) &&
            !empty($_POST['valor'])) {
            
                $nomeServico = $_POST['nome'];
                $descr = $_POST['descricao'];
                $valor = $_POST['valor'];

                $tipoServico = 1;

                if (inserirServico($pdo, $nomeServico, $descr, $valor, $tipoServico)) {
                    $_SESSION['mensagem'] = "Cadastro de serviço realizado com sucesso.";
                    header('Location: ../../pages/servico.php');
                    exit;
                }

        } else {
            $_SESSION['mensagem'] = "Campos vazios.";
            header('Location: ../../pages/servico.php');
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    header("Location: ../pages/servico.php");
    exit(); 
}

?>