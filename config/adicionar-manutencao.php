<?php
session_start();
if (!isset($_SESSION) OR $_SESSION['logado'] != true) {
    header("location: ../config/sair.php");
    exit();	
}

require '../config/config.php';
require '../function/funcoes_notas.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Verifica se os campos essenciais foram preenchidos
        if (!empty($_POST['km']) && !empty($_POST['placa'])) {
            
            // Captura os dados do formulário
            $time = $_POST['time-final'];
            $km = $_POST['km'];
            $defeito = $_POST['defeito'];
            $placa = $_POST['placa'];

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
            //$idManutencao = cadastrarManutencao($pdo, $time, $km, $defeito, $idVeiculo);

            // Cadastra os itens de serviço
            foreach ($itensServico as $item) {
                cadastrarItensManutencao($pdo, $idManutencao, $item['id'], $item['valor']);
            }

            // Cadastra os itens de produto (incluindo a quantidade)
            foreach ($itensProduto as $item) {
                cadastrarItensManutencao($pdo, $idManutencao, $item['id'], $item['valor'], $item['quantidade']);
                atualizarEstoqueProduto($pdo, $item['id'], $item['quantidade']);
            }

            

            
            echo "Manutenção cadastrada com sucesso!";
        } else {
            echo "Preencha todos os campos.";
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
