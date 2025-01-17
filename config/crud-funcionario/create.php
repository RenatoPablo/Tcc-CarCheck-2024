<?php
session_start();
if (!isset($_SESSION) OR $_SESSION['logado'] != true) {
    header("location: ../config/sair.php");
    exit();    
}

require '../config.php';
require '../api_cep.php';
require '../../function/funcoes_cliente.php';
require '../../function/funcoes_funcionario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_complemento = null;
    $id_ponto_ref = null;
    $cpf = null;
    $rg = null;
    $cnpj = null;
    $ie = null;
    $razao = null;
    $fantasia = null;

    $senha_incorreta = false;
    $verificar_campos = false;
    $sucesso_fisica = false;
    $sucesso_juridica = false;
    
    try {
        // Verifica se os campos obrigatórios estão preenchidos e se as senhas coincidem
        if (!empty($_POST['nome']) && 
            !empty($_POST['genero']) && 
            !empty($_POST['telefone']) && 
            !empty($_POST['email']) && 
            !empty($_POST['datadenascimento']) && 
            !empty($_POST['senha']) && 
            !empty($_POST['confirmarsenha']) &&
            $_POST['senha'] === $_POST['confirmarsenha']) {

            $nomePessoa = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
            $id_genero = intval($_POST['genero']);
            $numTelefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
            $enderecoEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $dataNasc = $_POST['datadenascimento'];
            $senha = $_POST['senha'];

            // Processamento do endereço
            if (!empty($_POST['cep']) &&
                !empty($_POST['cidade']) &&
                !empty($_POST['estado']) &&
                !empty($_POST['rua']) &&
                !empty($_POST['numero']) &&
                !empty($_POST['bairro'])) {

                $nomeEstado = is_array($_POST['estado']) ? $_POST['estado'][0] : $_POST['estado'];
                $sigla = $dadosCep['uf'];
                $nomeCidade = is_array($_POST['cidade']) ? $_POST['cidade'][0] : $_POST['cidade'];
                $cep = is_array($_POST['cep']) ? $_POST['cep'][0] : $_POST['cep'];
                $nomeRua = is_array($_POST['rua']) ? $_POST['rua'][0] : $_POST['rua'];
                $numeroCasa = is_array($_POST['numero']) ? $_POST['numero'][0] : $_POST['numero'];
                $nomeBairro = is_array($_POST['bairro']) ? $_POST['bairro'][0] : $_POST['bairro'];
                $descComplemento = is_array($_POST['complemento']) ? $_POST['complemento'][0] : $_POST['complemento'];
                $descPontoRef = is_array($_POST['ponto_ref']) ? $_POST['ponto_ref'][0] : $_POST['ponto_ref'];

                    // var_dump($cep);

                $id_estado = inserirEstado($pdo, $nomeEstado);
                $id_uf = inserirUf($pdo, $sigla, $id_estado);
                $id_cidade = inserirCidade($pdo, $nomeCidade, $id_uf);
                $id_cep = inserirCep($pdo, $cep, $id_cidade);
                $id_rua = cadastrarRua($pdo, $nomeRua);
                $id_numero_casa = cadastraNumeroCasa($pdo, $numeroCasa);
                $id_bairro = cadastrarBairro($pdo, $nomeBairro);

                if (!empty($_POST['complemento'])) {
                    $id_complemento = cadastrarComplemento($pdo, $descComplemento);
                }
                if (!empty($_POST['ponto_ref'])) {
                    $id_ponto_ref = cadastrarPontoRef($pdo, $descPontoRef);
                }
            }

            var_dump($id_cep);

            $id_pessoa = cadastrarPessoa($pdo, $nomePessoa, $numTelefone, $enderecoEmail, $senha, $dataNasc, $id_genero, $id_complemento, $id_ponto_ref, $id_estado, $id_uf, $id_cidade, $id_cep, $id_rua, $id_numero_casa, $id_bairro, !empty($descComplemento), !empty($descPontoRef));

            // Cadastro de funcionário com cargo e função
            if (!empty($_POST['cargo']) && !empty($_POST['funcao'])) {
                $nomeCargo = $_POST['cargo'];
                $nomeFuncao = $_POST['funcao'];
                
                $id_cargo = cadastrarCargo($pdo, $nomeCargo);
                $id_funcao = cadastrarFuncoes($pdo, $nomeFuncao);
                cadastrarFunci($pdo, $id_pessoa, $id_cargo, $id_funcao);
            }

            // Upload de imagem
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uploadDir = '../image/';
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFilePath = $uploadDir . $newFileName;
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array(strtolower($fileExtension), $allowedExtensions) && $_FILES['foto']['size'] < 5 * 1024 * 1024) {
                    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                        $sql = "UPDATE pessoas SET foto = :foto WHERE id_pessoa = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':foto', $uploadFilePath);
                        $stmt->bindParam(':id', $id_pessoa, PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        $_SESSION['mensagem'] = "Erro ao mover o arquivo.";
                    }
                } else {
                    $_SESSION['mensagem'] = "Formato de arquivo não permitido ou arquivo muito grande.";
                }
            }

            // Cadastro de pessoa física ou jurídica
            if (isset($_POST['tipo_pessoa'])) {
                if ($_POST['tipo_pessoa'] === 'fisica') {
                    $cpf = $_POST['cpf'];
                    $rg = $_POST['rg'];
                    cadastrarPessoaFisica($pdo, $cpf, $rg, $id_pessoa);
                    $_SESSION['mensagem'] = "Pessoa física cadastrada com sucesso";
                } elseif ($_POST['tipo_pessoa'] === 'juridica') {
                    $cnpj = $_POST['cnpj'];
                    $ie = $_POST['ie'];
                    $razao = $_POST['razao-social'];
                    $fantasia = $_POST['nome-fantasia'];
                    cadastrarPessoaJuridica($pdo, $cnpj, $ie, $razao, $fantasia, $id_pessoa);
                    $_SESSION['mensagem'] = "Pessoa jurídica cadastrada com sucesso";
                }
                header('Location: ../../pages/funcionario.php');
                exit;
            }
        } else {
            $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios ou verifique se as senhas coincidem.";
            header('Location: ../../pages/funcionario.php');
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    header("Location: ../../pages/funcionario.php");
    exit();
}
?>
