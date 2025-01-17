<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_funcionario.php';

try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['idFuncionario'])) {
            $id = intval($_POST['idFuncionario']);

            
            
            deleteFuncionario($pdo, $id);

            $_SESSION['mensagem'] = "Cliente excluído com sucesso.";
            header('Location: ../../pages/funcionario.php');
            exit;
            
        } else {
            echo "Nenhum item para excluir.";
        }
    } else {
        echo "Nenhum formulario enviado.";
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
}

?>