<?php

session_start();

require '../config.php';
require '../../function/funcoes_fornecedor.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['idFornecedor'])) {
            $id = $_POST['idFornecedor'];

            if(deleteFornecedor($pdo, $id)) {
                $_SESSION['mensagem'] = "Fornecedor excluído com sucesso";
                header('Location: ../../pages/fornecedor.php');
                exit;
            }

        }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado.";
        header('Location: ../../pages/fornecedor.php');
        exit;
    }
    
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/fornecedor.php');
    exit;
}

?>