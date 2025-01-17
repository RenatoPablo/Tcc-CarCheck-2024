<?php

session_start();


require '../config.php';
require '../../function/funcoes_notas.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Verifica se os campos essenciais foram preenchidos
        if (!empty($_POST['km']) && 
            !empty($_POST['placa'])) {
            
            // Captura os dados do formulário
            $time = $_POST['time-final'];
            $km = $_POST['km'];
            $defeito = $_POST['defeito'];
            $placa = $_POST['placa'];
            $valorTotal = $_POST['valorTotal'];
            
            $data = date("Y-m-d H:i:s");


            


            // Decodifica os itens de serviço e produto do JSON recebido
            $itensServico = json_decode($_POST['itemListServico'], true);
            $itensProduto = json_decode($_POST['itemListProduto'], true);

            // Verifica se os itens são arrays válidos
            if (!is_array($itensServico)) {
                $itensServico = [];
            }

            if (!is_array($itensProduto)) {
                $itensProduto = [];
            }

            // Obtém o ID do veículo com base na placa
            $idVeiculo = obterIdVeiculo($pdo, $placa);

            // Cadastra a manutenção e retorna o ID da manutenção cadastrada
            $idManutencao = cadastrarManutencao($pdo, $time, $km, $defeito, $idVeiculo, $valorTotal);

            // Cadastra os itens de serviço
            foreach ($itensServico as $item) {
                cadastrarItensManutencao($pdo, $idManutencao, $item['id'], $item['valor'], 1);
            }

            // Cadastra os itens de produto (incluindo a quantidade)
            foreach ($itensProduto as $item) {
                cadastrarItensManutencao($pdo, $idManutencao, $item['id'], $item['valor'], $item['quantidade']);
                $mensagem = atualizarEstoqueProduto($pdo, $item['id'], $item['quantidade']);
            }

            if ($mensagem !== null) {
                $_SESSION['mensagem'] = $mensagem;
                header('Location: ../../pages/ordem.php');
                exit;
            }

            

            

            $_SESSION['mensagem'] = "Ordem de serviço cadastrada com sucesso.";
            header('Location: ../../pages/ordem.php');
            exit;

            
            
        } else {
            $_SESSION['mensagem'] = "Preencha todos os campos.";
            header('Location: ../../pages/ordem.php');
            exit;
        }
    }
} catch (PDOException $e) {
    
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/ordem.php');
    exit;
}
?>
