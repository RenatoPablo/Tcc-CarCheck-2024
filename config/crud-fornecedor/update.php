<?php

session_start();

require '../config.php';
require '../../function/funcoes_fornecedor.php';

// Inicializa 'mensagem' como um array se não estiver definido
if (!isset($_SESSION['mensagem']) || !is_array($_SESSION['mensagem'])) {
    $_SESSION['mensagem'] = [];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['nome']) &&
            !empty($_POST['razao']) &&
            !empty($_POST['ie']) &&
            !empty($_POST['cnpj'])) {
                $id = $_POST['idFornecedor'];
                $nome = $_POST['nome'];
                $razao = $_POST['razao'];
                $ie = $_POST['ie'];
                $cnpj = $_POST['cnpj'];

                $nomeNovo = null;
                $razaoNovo = null;
                $ieNovo = null;
                $cnpjNovo = null;

                $dadosAtuais = readFornecedor($pdo, $id);

                

                if ($dadosAtuais['nome_fantasia'] !== $nome) {
                    $nomeNovo = $nome;
                }
                if ($dadosAtuais['razao_social'] !== $razao) {
                    $razaoNovo = $razao;
                }
                if ($dadosAtuais['ie'] !== $ie) {
                    $ieNovo = $ie;
                }
                if ($dadosAtuais['cnpj'] !== $cnpj) {
                    $cnpjNovo = $cnpj;
                }

                if ($nomeNovo !== null || $razaoNovo !== null || $ieNovo !== null || $cnpjNovo !== null) {
                    $arrayFornecedor = updateFornecedor($pdo, $id, $nomeNovo, $razaoNovo, $ieNovo, $cnpjNovo);
                    
                    
                    if ($arrayFornecedor[0] !== false) {
                        $_SESSION['mensagem'][] = "Nome fantasia atualizado.";
                    }
    
                    if ($arrayFornecedor[1] !== false) {
                        $_SESSION['mensagem'][] = "Razão social atualizado.";
                    }
    
                    if ($arrayFornecedor[2] !== false) {
                        $_SESSION['mensagem'][] = "Inscrição estadual atualizado.";
                    }
    
                    if ($arrayFornecedor[3] !== false) {
                        $_SESSION['mensagem'][] = "CNPJ atualizado.";
                    }
                } else {
                    $_SESSION['mensagem'][] = "Nenhum dado atualizado.";
                }

                $_SESSION['mensagem'] = implode(", ", $_SESSION['mensagem']);
                header('Location: ../../pages/fornecedor.php');
                exit;

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