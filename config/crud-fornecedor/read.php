<?php 

session_start();

require '../config.php';

try {
    $sql = "SELECT 
                id_fornecedor,
                nome_fantasia,
                razao_social,
                ie,
                cnpj
            FROM 
                fornecedores";
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/fornecedor.php');
    exit;
}


?>