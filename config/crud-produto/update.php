<?php 

session_start();

require '../config.php';
require '../../function/funcoes_produto.php';

// Inicializa 'mensagem' como um array se não estiver definido
if (!isset($_SESSION['mensagem']) || !is_array($_SESSION['mensagem'])) {
    $_SESSION['mensagem'] = [];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['idProduto']) && 
            !empty($_POST['nome']) &&
            !empty($_POST['descricao']) &&
            !empty($_POST['valor']) &&
            !empty($_POST['quantidade']) &&
            !empty($_POST['marca'])) {

                $idProduto = $_POST['idProduto'];
                $nomeProduto = $_POST['nome'];
                $descr = $_POST['descricao'];
                $valor = $_POST['valor'];
                $quant = intval($_POST['quantidade']);
                $marca = $_POST['marca'];
                
                $nomeProdutoNovo = null;
                $descrNovo = null;
                $valorNovo = null;
                $quantNovo = null;
                $marcaNovo = null;

                

                $dadosAtual = verifica($pdo, $idProduto);

                if ($dadosAtual['nome_servico_produto'] !== $nomeProduto) {
                    $nomeProdutoNovo = $nomeProduto;
                }
                if ($dadosAtual['descricao'] !== $descr) {
                    $descrNovo = $descr;
                }
                if ($dadosAtual['valor_servico_produto'] !== $valor) {
                    $valorNovo = $valor;
                }
                if ($dadosAtual['quantidade'] !== $quant) {
                    $quantNovo = $quant;
                }
                if ($dadosAtual['nome_marca_produto'] !== $marca) {
                    $marcaNovo = $marca;
                }

                if ($nomeProdutoNovo !== null || $descrNovo !== null || $valorNovo !== null || $quantNovo !== null || $marcaNovo !== null) {
                        $arrayProduto = updateProduto($pdo, $idProduto, $nomeProdutoNovo, $descrNovo, $valorNovo, $quantNovo, $marcaNovo);

                        

                        if ($arrayProduto[0] !== false) {
                            $_SESSION['mensagem'][] = "Nome do produto atualizado.";
                        }
                        if ($arrayProduto[1] !== false) {
                            $_SESSION['mensagem'][] = "Descrição atualizada.";
                        }
                        if ($arrayProduto[2] !== false) {
                            $_SESSION['mensagem'][] = "Valor atualizado.";
                        }
                        if ($arrayProduto[3] !== false) {
                            $_SESSION['mensagem'][] = "Quantidade atualizada.";
                        }
                        if ($arrayProduto[4] !== false) {
                            $_SESSION['mensagem'][] = "Marca atualizada.";
                        }
                } else {
                    $_SESSION['mensagem'][] = "Nenhum dado atualizado.";
                }
                
                    $_SESSION['mensagem'] = implode(", ", $_SESSION['mensagem']);
                    header('Location: ../../pages/produto.php');
                    exit;

            }
    } else {
        $_SESSION['mensagem'] = "Nenhum formulário enviado.";
        header('Location: ../../pages/produto.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/produto.php');
    exit;
}

?>