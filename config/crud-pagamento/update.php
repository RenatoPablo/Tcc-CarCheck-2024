<?php

session_start();

require '../config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['data_parcela'])) {
            $id = intval($_POST['id_pagamento']);
            $data = $_POST['data_parcela'];
            // Verifica se a data foi enviada corretamente
            if (!empty($data)) {
                // Converte a data para o formato YY-mm-dd
                $dataFormatada = DateTime::createFromFormat('Y-m-d', $data)->format('Y-m-d');
                
            } else {
                echo "Data não fornecida ou inválida.";
            }
            //var_dump($id);
            //var_dump($dataFormatada);

            $sql = "UPDATE pagamentos SET data_parcela = :data WHERE id_pagamento = :id";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([
                ':data' => $dataFormatada,
                ':id'   => $id
            ])) {
                $_SESSION['mensagem'] = "Data da parcela alterada.";
            } else {
                $_SESSION['mensagem'] = "Erro ao alterar data.";
            }

            header('Location: ../../pages/ordem.php');
            exit;
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>