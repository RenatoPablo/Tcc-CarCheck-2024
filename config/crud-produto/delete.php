<?php 

session_start();

require '../config.php';
require '../../function/funcoes_produto.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idProduto'])) {
            $id = $_POST['idProduto'];

            if(deleteProduto($pdo, $id)) {
                $_SESSION['mensagem'] = "Serviço excluído com sucesso";
                header('Location: ../../pages/produto.php');
                exit;
            }

        }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado.";
        header('Location: ../../pages/produto.php');
        exit;
    }
    
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/produto.php');
    exit;
}

?>