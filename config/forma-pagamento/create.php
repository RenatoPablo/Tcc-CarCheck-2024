<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_forma_pagamento.php';
try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        if(!empty($_POST['nomeItem'])) {

            $formaPagamento = htmlspecialchars($_POST['nomeItem'], ENT_QUOTES, 'UTF-8');

            $idForma = cadastrarForma($pdo, $formaPagamento);

            $_SESSION['mensagem'] = "Forma de pagamento cadastrada com sucesso.";
            header('Location: ../../pages/forma-pagamento.php');
            exit;
        } else {
            echo "Digite a forma de pagamento";
            exit;
        }
    } else {
        echo "Nenhum formulario enviado";
        exit;
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}

?>