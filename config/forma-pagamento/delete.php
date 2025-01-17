<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_forma_pagamento.php';

try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['id_item'])) {
            $id = intval($_POST['id_item']);
            
            deleteForma($pdo, $id);

            $_SESSION['mensagem'] = "Forma pagamento excluída com sucesso.";
            header('Location: ../../pages/forma-pagamento.php');
            exit;
            
        } else {
            echo "Nenhum item para excluir.";
        }
    } else {
        echo "Nenhum formulario enviado.";
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}

?>