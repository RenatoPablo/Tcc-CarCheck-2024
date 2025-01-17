<?php 

session_start();

require '../config.php';
require '../../function/funcoes_servico.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idServico'])) {
            $id = $_POST['idServico'];

            if(deleteServico($pdo, $id)) {
                $_SESSION['mensagem'] = "Serviço excluído com sucesso";
                header('Location: ../../pages/servico.php');
                exit;
            }

        }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado.";
        header('Location: ../../pages/servico.php');
        exit;
    }
    
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/servico.php');
    exit;
}

?>