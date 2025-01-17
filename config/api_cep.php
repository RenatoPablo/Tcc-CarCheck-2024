<?php
header('Content-Type: application/json');

// Conexão
require 'config.php';

// Função para buscar dados do cep na API ViaCEP
function buscarDadosCep($cep) {
    // Validar o formato do CEP (8 dígitos)
    if (!preg_match('/^[0-9]{8}$/', $cep)) {
        return ['erro' => true, 'mensagem' => 'CEP inválido, deve conter 8 dígitos numéricos'];
    }

    // Montar a URL da API
    $url = "https://viacep.com.br/ws/{$cep}/json";

    // Fazer requisição para a API
    $response = @file_get_contents($url);

    // Verificar se houve sucesso na requisição
    if ($response === FALSE) {
        return ['erro' => true, 'mensagem' => 'Falha ao se conectar à API do ViaCEP'];
    }

    // Decodificar resposta JSON
    $dadosCep = json_decode($response, true);

    // Verificar se o CEP foi encontrado
    if (isset($dadosCep['erro']) && $dadosCep['erro'] === true) {
        return ['erro' => true, 'mensagem' => 'CEP não encontrado'];
    }

    // Verificar se as chaves necessárias existem
    if (!isset($dadosCep['uf']) || !isset($dadosCep['localidade']) || !isset($dadosCep['bairro'])) {
        return ['erro' => true, 'mensagem' => 'Dados incompletos retornados pela API do ViaCEP'];
    }

    // Retornar os dados completos
    return $dadosCep;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Captura o CEP enviado pelo formulário
        $cep = isset($_POST['cep']) ? $_POST['cep'] : null;

        if ($cep) {
            // Buscar dados do CEP
            $dadosCep = buscarDadosCep($cep);

            // Verificar se houve erro ao buscar o CEP
            if (isset($dadosCep['erro']) && $dadosCep['erro'] === true) {
                echo json_encode(['success' => false, 'message' => 'CEP inválido']);
                exit();  // Para a execução após a mensagem de erro
            }

            // Retorna a resposta em formato JSON
            echo json_encode([
                'cidade' => $dadosCep['localidade'],
                'estado' => $dadosCep['uf'],  // Corrigido para 'uf' que representa o estado
                'bairro' => $dadosCep['bairro'], // Adicionado o bairro
                'cep' => $cep,
                'mensagem' => 'Dados do CEP retornados com sucesso!'
            ]);

        } else {
            // Caso o CEP não tenha sido informado
            echo json_encode(['success' => false, 'message' => 'CEP não informado']);
        }

    } catch (PDOException $e) {
        // Captura e exibe erros de banco de dados
        echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
    }
}


?>