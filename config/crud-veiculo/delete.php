<?php 

session_start();

require '../../config/config.php';
require '../../function/funcoes_veiculo.php';


try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['idVeiculo'])) {
            $id = intval($_POST['idVeiculo']);
            
            deleteVeiculo($pdo, $id);

            $_SESSION['mensagem'] = "Veiculo excluído com sucesso.";
            header('Location: ../../pages/veiculo.php');
            exit;
            
        } else {
            echo "Nenhum item para excluir.";
            header('Location: ../../pages/veiculo.php');
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulario enviado.";
        header('Location: ../../pages/veiculo.php');
        exit;
    }
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    header('Location: ../../pages/veiculo.php');
    exit;
}

?>