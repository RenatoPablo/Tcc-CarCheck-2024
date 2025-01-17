<?php

session_start();

require '../config.php';
require '../../function/funcoes_servico.php';


try {

    // Inicializa 'mensagem' como um array se não estiver definido
    if (!isset($_SESSION['mensagem']) || !is_array($_SESSION['mensagem'])) {
        $_SESSION['mensagem'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['nome']) && 
            !empty($_POST['descricao']) &&
            !empty($_POST['valor']) &&
            !empty($_POST['idServico'])) {
                $idServico = $_POST['idServico'];
                $nomeServico = $_POST['nome'];
                $descr = $_POST['descricao'];
                $valor = $_POST['valor'];
                
                $nomeServicoNovo = null;
                $descrNovo = null;
                $valorNovo = null;

                $dadosAtual = verificaServico($pdo, $idServico);

                if ($dadosAtual['nome_servico_produto'] !== $nomeServico) {
                    $nomeServicoNovo = $nomeServico;
                }
                if ($dadosAtual['descricao'] !== $descr) {
                    $descrNovo = $descr;
                }
                if ($dadosAtual['valor_servico_produto'] !== $valor) {
                    $valorNovo = $valor;
                }
                

                if ($nomeServicoNovo !== null || $descrNovo !== null || $valorNovo !== null) {
                    $arrayServico = updateServico($pdo, $idServico, $nomeServicoNovo, $descrNovo, $valorNovo);
                        if ($arrayServico[0] !== false) {
                            $_SESSION['mensagem'][] = "Nome atualizado.";
                        }
                        if ($arrayServico[1] !== false) {
                            $_SESSION['mensagem'][] = "Descrição atualizada.";
                        }
                        if ($arrayServico[2] !== false) {
                            $_SESSION['mensagem'][] = "Valor Atualizado.";
                        }
                } else {
                    $_SESSION['mensagem'][] = "Nenhum dado atualizado.";
                }

                $_SESSION['mensagem'] = implode(", ", $_SESSION['mensagem']);
                header('Location: ../../pages/servico.php');
                exit;
            }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado.";
        header('Location: ../../pages/servico.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro" . $e->getMessage();
    header('Location: ../../pages/servico.php');
    exit;
}

?>