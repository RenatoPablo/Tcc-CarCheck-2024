<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_forma_pagamento.php';

try {
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        if(!empty($_POST['nomeItem'])) {
            $idForma = intval($_POST['id_item']);
            $forma = htmlspecialchars($_POST['nomeItem'], ENT_QUOTES, 'UTF-8');
            
            updateForma($pdo, $forma, $idForma);
            $_SESSION['mensagem'] = "Dados alterados com sucesso.";
            header('Location: ../../pages/forma-pagamento.php');
            exit;
        }

    } else {
        echo "Nenhum formulário enviado.";
    }

} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}
?>