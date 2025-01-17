<?php 

session_start();

require '../config.php';
require '../../function/funcoes_produto.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['nome']) &&
            !empty($_POST['descricao']) &&
            !empty($_POST['valor']) &&
            !empty($_POST['quantidade']) &&
            !empty($_POST['marca'])) {
                $nome = $_POST['nome'];
                $descr = $_POST['descricao'];
                $valor = $_POST['valor'];
                $quant = $_POST['quantidade'];
                $marca = $_POST['marca'];

                $idMarca = inserirMarcaProduto($pdo, $marca);

                if (inserirProduto($pdo, $nome, $descr, $valor, $quant, $idMarca)) {
                    $_SESSION['mensagem'] = "Produto cadastrado com sucesso!";
                    header('Location: ../../pages/produto.php');
                    exit;                    
                }
                
            } else {
                $_SESSION['mensagem'] = "Cadastro não concluído, verifique os campos.";
                header('Location: ../../pages/produto.php');
                exit;
            }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado";
        header('Location: ../../pages/produto.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/produto.php');
    exit;
}

?>