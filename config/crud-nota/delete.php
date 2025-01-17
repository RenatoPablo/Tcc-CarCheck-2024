<?php 

session_start();

require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['idManutencao'])) {
            $idManutencao = intval($_POST['idManutencao']);
            $sql = "DELETE FROM manutencoes WHERE id_manutencao = :idManutencao";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':idManutencao' => $idManutencao]);

            $_SESSION['mensagem'] = "Manutenção excluída";
            header('Location: ../../pages/ordem.php');
            exit;
        }   
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

?>