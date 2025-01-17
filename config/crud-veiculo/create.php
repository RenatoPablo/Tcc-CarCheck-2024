<?php 
    session_start();
    if (!isset($_SESSION) OR $_SESSION['logado'] != true) {
        header("location: ../config/sair.php");
        exit();	
    }

require '../config.php';
require '../../function/funcoes_veiculo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    
        if (!empty($_POST['proprietario']) &&
            !empty($_POST['placa']) &&
            !empty($_POST['cor']) &&
            !empty($_POST['tipo']) &&
            !empty($_POST['modelo']) &&
            !empty($_POST['marca'])
        ) {
            $cor = $_POST['cor'];
            $nomeModelo = $_POST['modelo'];
            $marca = $_POST['marca'];
            $tipo_veiculo = $_POST['tipo'];
            $placa = $_POST['placa'];
            $nomeProprie = $_POST['proprietario'];

            // var_dump("Cor: " . $cor,"Modelo: " . $nomeModelo,"Marca: " . $marca,"Tipo Veiculo: " . $tipo_veiculo,"Placa: " . $placa,"Proprietario: " . $nomeProprie);

            $idCor = cadastrarCor($pdo, $cor);

            $idModelo = cadastrarModelo($pdo, $nomeModelo);

            $idMarca = cadastrarMarcas($pdo, $marca);

            $idTipoVeiculo = cadastrarTipoVeiculo($pdo, $tipo_veiculo);

            $idProprietario = buscarProprietario($pdo, $nomeProprie);

            $idVeiculo = cadastrarVeiculo($pdo, $idProprietario, $placa, $idCor, $idModelo, $idMarca, $idTipoVeiculo);

            $_SESSION['mensagem'] = "Veiculo cadastrado com sucesso.";
            header('Location: ../../pages/veiculo.php');
            exit;
        } else {
            echo "Campos vazios.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    header("Location: ../pages/veiculo.php");
    exit(); 
}

?>