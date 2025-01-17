<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_cliente.php';

try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['id'])) {
            $id = intval($_POST['id']);
            
            deletePessoa($pdo, $id);

            $_SESSION['mensagem'] = "Cliente excluído com sucesso.";
            header('Location: ../../pages/cliente.php');
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