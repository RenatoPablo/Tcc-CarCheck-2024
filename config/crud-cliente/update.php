<?php 
session_start(); // Inicia a sessão para usar variáveis de sessão

require '../config.php';
require '../../function/funcoes_cliente.php';

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
        
        // Campos adicionais para pessoa física e jurídica
        $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
        $rg = isset($_POST['rg']) ? $_POST['rg'] : null;
        $cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : null;
        $ie = isset($_POST['ie']) ? $_POST['ie'] : null;
        $razao_social = isset($_POST['razao']) ? $_POST['razao'] : null;
        $nome_fantasia = isset($_POST['fantasia']) ? $_POST['fantasia'] : null;

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

        // Verificação e atualização de campos
        if (readRua($pdo, $idRua) !== $rua) {
            updateRua($pdo, $rua, $idRua);
            $_SESSION['mensagem'][] = "Rua atualizada com sucesso.";
        }
        if (readEstado($pdo, $idEstado) !== $estado) {
            updateEstado($pdo, $estado, $idEstado);
            $_SESSION['mensagem'][] = "Estado atualizado com sucesso.";
        }
        if (readUf($pdo, $idUf) !== $uf) {
            updateUf($pdo, $uf, $idUf);
            $_SESSION['mensagem'][] = "UF atualizada com sucesso.";
        }
        if (readCidade($pdo, $idCidade) !== $cidade) {
            updateCidade($pdo, $cidade, $idCidade);
            $_SESSION['mensagem'][] = "Cidade atualizada com sucesso.";
        }
        if (readCep($pdo, $idCep) !== $cep) {
            updateCep($pdo, $cep, $idCep);
            //$_SESSION['mensagem'][] = "CEP atualizado com sucesso.";
        }
        if (readComplemento($pdo, $idComplemento) !== $complemento) {
            updateComplemento($pdo, $complemento, $idComplemento);
            $_SESSION['mensagem'][] = "Complemento atualizado com sucesso.";
        }
        if (readReferencia($pdo, $idReferencia) !== $referencia) {
            updateReferencia($pdo, $referencia, $idReferencia);
            $_SESSION['mensagem'][] = "Ponto de Referência atualizado com sucesso.";
        }
        if (readNumero($pdo, $idNumero) !== $numero) {
            updateNumero($pdo, $numero, $idNumero);
            $_SESSION['mensagem'][] = "Número atualizado com sucesso.";
        }
        if (readBairro($pdo, $idBairro) !== $bairro) {
            updateBairro($pdo, $bairro, $idBairro);
            $_SESSION['mensagem'][] = "Bairro atualizado com sucesso.";
        }

        // Atualização de dados na tabela `pessoas`
        if ($fotoNome) {
            $sql = "UPDATE pessoas SET nome_pessoa = :nome, endereco_email = :email, numero_telefone = :telefone, data_nasc = :data_nascimento, foto = :foto WHERE id_pessoa = :id";
        } else {
            $sql = "UPDATE pessoas SET nome_pessoa = :nome, endereco_email = :email, numero_telefone = :telefone, data_nasc = :data_nascimento WHERE id_pessoa = :id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        
        if ($fotoNome) {
            $stmt->bindParam(':foto', $fotoNome);
        }

        if ($stmt->execute()) {
            $_SESSION['mensagem'][] = "Dados pessoais atualizados com sucesso.";
        }

        // Verifica e atualiza pessoa física
        if ($cpf && $rg) {
            $dadosPessoaFisica = readPessoaFisica($pdo, $id); // Retorna array com 'cpf' e 'rg'
            
            if ($dadosPessoaFisica['cpf'] !== $cpf) {
                updatePessoaFisicaCpf($pdo, $cpf, $id);
                $_SESSION['mensagem'][] = "CPF atualizado com sucesso.";
            }

            if ($dadosPessoaFisica['rg'] !== $rg) {
                updatePessoaFisicaRg($pdo, $rg, $id);
                $_SESSION['mensagem'][] = "RG atualizado com sucesso.";
            }
        }

        // Verifica e atualiza pessoa jurídica
        if ($cnpj && $ie) {
            $dadosPessoaJuridica = readPessoaJuridica($pdo, $id); // Retorna array com 'cnpj', 'ie', 'razao_social', 'nome_fantasia'
            
            if ($dadosPessoaJuridica['cnpj'] !== $cnpj) {
                updatePessoaJuridicaCnpj($pdo, $cnpj, $id);
                $_SESSION['mensagem'][] = "CNPJ atualizado com sucesso.";
            }

            if ($dadosPessoaJuridica['ie'] !== $ie) {
                updatePessoaJuridicaIe($pdo, $ie, $id);
                $_SESSION['mensagem'][] = "Inscrição Estadual atualizada com sucesso.";
            }

            if ($dadosPessoaJuridica['razao_social'] !== $razao_social) {
                updatePessoaJuridicaRazaoSocial($pdo, $razao_social, $id);
                $_SESSION['mensagem'][] = "Razão Social atualizada com sucesso.";
            }

            if ($dadosPessoaJuridica['nome_fantasia'] !== $nome_fantasia) {
                updatePessoaJuridicaNomeFantasia($pdo, $nome_fantasia, $id);
                $_SESSION['mensagem'][] = "Nome Fantasia atualizado com sucesso.";
            }
        }

        // Se $_SESSION['mensagem'] for um array, concatena as mensagens em uma string
        if (is_array($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])) {
            $_SESSION['mensagem'] = implode(" ", $_SESSION['mensagem']);
        } else {
            $_SESSION['mensagem'] = "Nenhuma alteração realizada.";
        }

        header('Location: ../../pages/cliente.php');
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro: Dados insuficientes.";
        header('Location: ../../pages/cliente.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    header('Location: ../../pages/cliente.php');
    exit;
}
?>
