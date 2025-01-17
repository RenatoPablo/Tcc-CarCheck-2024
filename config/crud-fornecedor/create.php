<?php 

session_start();

require '../config.php';
require '../../function/funcoes_fornecedor.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['nome']) &&
            !empty($_POST['razao']) &&
            !empty($_POST['ie']) &&
            !empty($_POST['cnpj'])) {
                $nome = $_POST['nome'];
                $razao = $_POST['razao'];
                $ie = $_POST['ie'];
                $cnpj = $_POST['cnpj'];

                $idFornecedor = inserirFornecedor($pdo, $nome, $razao, $ie, $cnpj);

                
                if ($idFornecedor) {
                    $_SESSION['mensagem'] = "Fornecedor cadastrado com sucesso.";
                    header('Location: ../../pages/fornecedor.php');
                    exit;
                } else {
                    $_SESSION['mensagem'] = "Erro ao cadastrar.";
                    header('Location: ../../pages/fornecedor.php');
                    exit;
                }
            } else {
                $_SESSION['mensagem'] = "Dados incompletos.";
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