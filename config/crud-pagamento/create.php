<?php

session_start();
require '../config.php';
require '../../function/funcoes_notas.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['id_manutencao']) &&
            !empty($_POST['valor_pagamento']) &&
            !empty($_POST['data_pagamento']) &&
            !empty($_POST['formaPagamento']) &&
            !empty($_POST['parcela'])) {

            $idManutencao = intval($_POST['id_manutencao']);
            $valorTotal = floatval($_POST['valor_pagamento']);
            $idForma = intval($_POST['formaPagamento']);
            $parcelas = intval($_POST['parcela']);

            // Verifica e converte a data inicial
            if (isset($_POST['data_pagamento'])) {
                $dataPagamento = DateTime::createFromFormat('Y-m-d', $_POST['data_pagamento']);

                if (!$dataPagamento) {
                    $_SESSION['mensagem'] = "Formato de data inválido.";
                    header('Location: ../../pages/ordem.php');
                    exit;
                }
            } else {
                $_SESSION['mensagem'] = "Data de pagamento não fornecida.";
                header('Location: ../../pages/ordem.php');
                exit;
            }

            if ($parcelas > 1) {
                $valorParcela = $valorTotal / $parcelas;
            
                // Loop para inserir cada parcela
                for ($i = 1; $i <= $parcelas; $i++) {
                    $codigo = sprintf("%d%02d", $idManutencao, $i); // Código único para a parcela
                    $dataParcela = $dataPagamento->format('Y-m-d'); // Data formatada para a parcela atual
                    
                    // Inserção de parcela
                    insertPagamento($pdo, $codigo, $valorParcela, $dataParcela, $i, $idForma, $idManutencao);
                    
                    // Incrementa a data para a próxima parcela
                    $dataPagamento->modify('+1 month');
                    
                    
                }
                
                $_SESSION['mensagem'] = $parcelas . " Parcelas adicionas."; // Debug
            } else {
                // Inserção para uma única parcela
                $codigo = sprintf("%d%02d", $idManutencao, 1);
                $dataParcela = $dataPagamento->format('Y-m-d');
                insertPagamento($pdo, $codigo, $valorTotal, $dataParcela, 1, $idForma, $idManutencao);
                $_SESSION['mensagem'] = "Parcela única inserida."; // Debug
            }
            

            // Marca a manutenção como faturada
            faturamentoTrue($pdo, $idManutencao);

            // Redireciona após a conclusão
            header('Location: ../../pages/ordem.php');
            exit;
        } else {
            $_SESSION['mensagem'] = "Campos incompletos.";
            header('Location: ../../pages/ordem.php');
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "Formulário não enviado.";
        header('Location: ../../pages/ordem.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
