<?php 
session_start();

require '../config.php';
require '../../function/funcoes_cliente.php';
require '../../function/funcoes_funcionario.php'; // Inclui as funções específicas para o funcionário


        

try {
    // Inicializa 'mensagem' como um array, caso não esteja definido
    if (!isset($_SESSION['mensagem']) || !is_array($_SESSION['mensagem'])) {
        $_SESSION['mensagem'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Captura os dados do formulário
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['dataNascimento'];
        
        $idRua = $_POST['idRua'];
        $rua = $_POST['rua'];
        $idNumero = $_POST['idNumeroCasa'];
        $numero = $_POST['numero'];
        $idBairro = $_POST['idBairro'];
        $bairro = $_POST['bairro'];
        $idCidade = $_POST['idCidade'];
        $cidade = $_POST['cidade'];
        $idEstado = $_POST['idEstado'];
        $estado = $_POST['estado'];
        $idUf = $_POST['idUf'];
        $uf = $_POST['uf'];
        $idCep = $_POST['idCep'];
        $cep = $_POST['cep'];
        $idComplemento = $_POST['idComplemento'];
        $complemento = $_POST['complemento'];
        $idReferencia = $_POST['idPontoRef'];
        $referencia = $_POST['referencia'];
        
        // Campos específicos do funcionário
        $id_cargo = $_POST['idCargo'];
        $cargo = $_POST['cargo'];
        $id_funcao = $_POST['idFuncao'];    
        $funcao = $_POST['funcao'];

        // Diretório para upload de foto
        $diretorioDestino = '../uploads/fotos/';
        $fotoNome = null;

        

        // Verifica se um arquivo foi enviado
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoTmpName = $_FILES['foto']['tmp_name'];
            $fotoNome = basename($_FILES['foto']['name']);
            $fotoCaminho = $diretorioDestino . $fotoNome;

            if (move_uploaded_file($fotoTmpName, $fotoCaminho)) {
                $_SESSION['mensagem'][] = "Foto carregada com sucesso.";
            } else {
                $_SESSION['mensagem'][] = "Erro ao fazer o upload da foto.";
            }
        }

        // Verificação e atualização de campos gerais (exemplo: rua, cidade, etc.)
        if (readRua($pdo, $idRua) !== $rua) {
            updateRua($pdo, $rua, $idRua);
            $_SESSION['mensagem'][] = "Rua atualizada com sucesso.";
        }
        if (readBairro($pdo, $idBairro) !== $bairro) {
            updateBairro($pdo, $bairro, $idBairro);
            $_SESSION['mensagem'][] = "Bairro atualizado com sucesso.";
        }

        $pessoaAtual = readPessoa($pdo, $id);

        if (
            $pessoaAtual['nome_pessoa'] !== $nome ||
            $pessoaAtual['endereco_email'] !== $email ||
            $pessoaAtual['numero_telefone'] !== $telefone ||
            $pessoaAtual['data_nasc'] !== $data_nascimento
        ) {
            // Atualização dos dados da tabela `pessoas`
            updatePessoa($pdo, $id, $nome, $email, $telefone, $data_nascimento, $fotoNome);
            $_SESSION['mensagem'][] = "Dados pessoais atualizados com sucesso.";
        }

        
        if (readCargo($pdo, $id_cargo) !== $cargo) {
            $cargoVerifica = updateCargo($pdo, $cargo, $id_cargo);
            $_SESSION['mensagem'][] = "Dados do cargo atualizados com sucesso.";
        }
        if (readFuncao($pdo, $id_funcao) !== $funcao) {
            $funcaoVerifica = updateFuncao($pdo, $funcao, $id_funcao);
            $_SESSION['mensagem'][] = "Dados da função atualizados com sucesso";
        }

        if ($cargoVerifica && $funcaoVerifica) {
            
            $_SESSION['mensagem'][] = "Dados do funcionário atualizados com sucesso.";
        }

        // Redireciona e concatena as mensagens em uma string
        $_SESSION['mensagem'] = implode(" ", $_SESSION['mensagem']);
        header('Location: ../../pages/funcionario.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro: Dados insuficientes.";
        header('Location: ../../pages/funcionario.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/funcionario.php');
    exit;
}
?>
