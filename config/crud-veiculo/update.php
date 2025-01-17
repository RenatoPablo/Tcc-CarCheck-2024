<?php
session_start();

require '../config.php';
require '../../function/funcoes_veiculo.php';

try {
    // Inicializa 'mensagem' como um array se não estiver definido
    if (!isset($_SESSION['mensagem']) || !is_array($_SESSION['mensagem'])) {
        $_SESSION['mensagem'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Captura os valores do formulário
        $idVeiculo = $_POST['idVeiculo'];
        $idProprietario = $_POST['idPessoa'];
        $idCor = $_POST['idCor'];
        $idTipoVeiculo = $_POST['idTipoVeiculo'];
        $idModelo = $_POST['idModelo'];
        $idMarca = $_POST['idMarca'];
        $placa = $_POST['placa'];
        $status = $_POST['status'];
        $nomePessoa = $_POST['proprietario'];
        $modelo = $_POST['modelo'];
        $tipoVeiculo = $_POST['tipoVeiculo'];
        $cor = $_POST['cor'];
        $marca = $_POST['marca'];

        // Inicialize os IDs com os valores atuais
        $idCorNova = $idCor;
        $idNovoTipoVeiculo = $idTipoVeiculo;
        $idNovoModelo = $idModelo;
        $idNovaMarca = $idMarca;
        $mudaPlaca = false;
        $mudaStatus = false;
        $alteracaoRealizada = false;

        // Verificação do proprietário
        $pessoaAtual = readProprietario($pdo, $idProprietario);
        if ($pessoaAtual !== $nomePessoa) {
            $idNovoProprietario = readNovoProprietario($pdo, $nomePessoa);
            if (updateProprietario($pdo, $idNovoProprietario, $idVeiculo)) {
                $_SESSION['mensagem'][] = "Novo proprietário adicionado";
                $alteracaoRealizada = true;
            }
        }

        // Verificação da cor
        $corAtual = readCor($pdo, $idCor);
        if ($corAtual !== $cor) {
            $idCorNova = cadastrarCor($pdo, $cor);
            $_SESSION['mensagem'][] = "Cor atualizada";
            $alteracaoRealizada = true;
        }

        // Verificação do tipo de veículo
        $tipoVeiculoAtual = readTipoVeiculo($pdo, $idTipoVeiculo);
        if ($tipoVeiculoAtual !== $tipoVeiculo) {
            $idNovoTipoVeiculo = cadastrarTipoVeiculo($pdo, $tipoVeiculo);
            $_SESSION['mensagem'][] = "Tipo de Veículo atualizado";
            $alteracaoRealizada = true;
        }

        // Verificação do modelo
        $modeloAtual = readModelo($pdo, $idModelo);
        if ($modeloAtual !== $modelo) {
            $idNovoModelo = cadastrarModelo($pdo, $modelo);
            $_SESSION['mensagem'][] = "Modelo atualizado";
            $alteracaoRealizada = true;
        }

        // Verificação da marca
        $marcaAtual = readMarca($pdo, $idMarca);
        if ($marcaAtual !== $marca) {
            $idNovaMarca = cadastrarMarcas($pdo, $marca);
            $_SESSION['mensagem'][] = "Marca atualizada";
            $alteracaoRealizada = true;
        }

        // Verificação da placa
        $veiculoAtual = readVeiculo($pdo, $idVeiculo);
        if ($veiculoAtual['placa'] !== $placa) {
            $mudaPlaca = true;
            $placaNova = $placa;
            $_SESSION['mensagem'][] = "Placa atualizada";
            $alteracaoRealizada = true;
        } else {
            $placaNova = $veiculoAtual['placa']; // Valor atual caso não alterado
        }

        // Verificação do status
        if ($veiculoAtual['status'] !== $status) {
            $mudaStatus = true;
            $statusNovo = $status;
            $_SESSION['mensagem'][] = "Status atualizado";
            $alteracaoRealizada = true;
        } else {
            $statusNovo = $veiculoAtual['status']; // Valor atual caso não alterado
        }

        // Executa a atualização se houve mudanças
        if ($alteracaoRealizada) {
            updateVeiculos($pdo, $idVeiculo, $idCorNova, $idNovoTipoVeiculo, $idNovoModelo, $idNovaMarca, $placaNova, $statusNovo);
            $_SESSION['mensagem'][] = "Atualização de veículo realizada com sucesso";
        } else {
            $_SESSION['mensagem'] = "Nenhuma alteração realizada.";
        }

        header('Location: ../../pages/veiculo.php');
        exit;

    } else {
        $_SESSION['mensagem'] = "Erro: Dados insuficientes.";
        header('Location: ../../pages/veiculo.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/veiculo.php');
    exit;
}
