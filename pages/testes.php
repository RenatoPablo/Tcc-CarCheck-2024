<?php

if (!empty($_POST['nomeServico']) &&
   !empty($_POST['descrServico']) &&
   !empty($_POST['valorServico']) &&
   !empty($_POST['option'])) {

        $nomeServico = htmlspecialchars($_POST['nomeServikco'], ENT_QUOTES, 'UTF-8');
        $descrServico = htmlspecialchars($_POST['descr'], ENT_QUOTES, 'UTF-8');
        $valorServico = floatval($_POST['valorServico']);
        
        
        $id_servico = cadastrarServico($pdo, $nomeServico, $descrServico, $valorServico, $idTipo);
   } else {
    //mostrar o popup sobre oq esta faltando
   }

if (!empty($_POST['nomeServico']) &&
    !empty($_POST['descrServico']) &&
    !empty($_POST['valorServico']) &&
    !empty($_POST['marcaProduto']) &&
    !empty($_POST['option'])) {
        $nomeProduto = htmlspecialchars($_POST['nomeProduto'], ENT_QUOTES, 'UTF-8');
        $descrProduto = htmlspecialchars($_POST['descrProduto'], ENT_QUOTES, 'UTF-8');
        $valorProduto = floatval($_POST['valorProduto']);
        $marcaProduto = htmlspecialchars($_POST['marcaProduto'], ENT_QUOTES, 'UTF-8');

        $id_marca = cadastrarMarca($pdo, $marcaProduto);
        $id_produto = cadastrarProduto($pdo, $nomeProduto, $descrProduto, $valorProduto, $idTipo, $id_marca);
    }


?>