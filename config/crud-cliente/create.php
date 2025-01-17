<?php
session_start();
if (!isset($_SESSION) OR $_SESSION['logado'] != true) {
    header("location: ../config/sair.php");
    exit();
}

require '../config.php';
include '../api_cep.php';
require '../../function/funcoes_cliente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Variáveis para complementar a lógica
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
        // Verifica se os campos obrigatórios estão preenchidos
        if (!empty($_POST['nome']) && 
            !empty($_POST['genero']) && 
            !empty($_POST['telefone']) && 
            !empty($_POST['email']) && 
            !empty($_POST['datadenascimento']) && 
            !empty($_POST['senha']) && 
            !empty($_POST['confirmarsenha'])) {

            if ($_POST['senha'] === $_POST['confirmarsenha']) {

                $nomePessoa = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
                $id_genero = intval($_POST['genero']);
                $numTelefone = htmlspecialchars($_POST['telefone'], ENT_QUOTES, 'UTF-8');
                $enderecoEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $dataNasc = $_POST['datadenascimento'];
                $senha = $_POST['senha'];

                // Verifica os campos de endereço
                if (!empty($_POST['cep']) &&
                    !empty($_POST['cidade']) &&
                    !empty($_POST['estado']) &&
                    !empty($_POST['rua']) &&
                    !empty($_POST['numero']) &&
                    !empty($_POST['bairro'])) {

                    // Processamento do endereço
                    $nomeEstado = $_POST['estado'];
                    $sigla = $dadosCep['uf']; // Isso precisa ser validado com base na resposta do CEP
                    $nomeCidade = $_POST['cidade'];
                    $cep = $_POST['cep'];
                    $nomeRua = $_POST['rua'];
                    $numeroCasa = $_POST['numero'];
                    $nomeBairro = $_POST['bairro'];
                    $descComplemento = $_POST['complemento'] ?? null;
                    $descPontoRef = $_POST['ponto_ref'] ?? null;

                    // Chamar funções para inserir os dados relacionados ao endereço
                    $id_estado = inserirEstado($pdo, $nomeEstado);
                    $id_uf = inserirUf($pdo, $sigla, $id_estado);
                    $id_cidade = inserirCidade($pdo, $nomeCidade, $id_uf);
                    $id_cep = inserirCep($pdo, $cep, $id_cidade);
                    $id_rua = cadastrarRua($pdo, $nomeRua);
                    $id_numero_casa = cadastraNumeroCasa($pdo, $numeroCasa);
                    $id_bairro = cadastrarBairro($pdo, $nomeBairro);

                    // Verifica e insere complemento e ponto de referência se fornecidos
                    if (!empty($descComplemento)) {
                        $id_complemento = cadastrarComplemento($pdo, $descComplemento);
                    }
                    if (!empty($descPontoRef)) {
                        $id_ponto_ref = cadastrarPontoRef($pdo, $descPontoRef);
                    }

                    // Cadastra a pessoa
                    $id_pessoa = cadastrarPessoa($pdo, $nomePessoa, $numTelefone, $enderecoEmail, $senha, $dataNasc, $id_genero, $id_complemento, $id_ponto_ref, $id_estado, $id_uf, $id_cidade, $id_cep, $id_rua, $id_numero_casa, $id_bairro, $id_complemento, $id_ponto_ref);

                    // Lógica de upload de imagem (opcional)
                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['foto']['tmp_name'];
                        $fileName = $_FILES['foto']['name'];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                            $newFileName = uniqid() . '.' . $fileExtension;
                            $uploadDir = '../image/';
                            $uploadFilePath = $uploadDir . $newFileName;

                            // Verifica se o diretório existe, senão cria
                            if (!file_exists($uploadDir)) {
                                mkdir($uploadDir, 0777, true); // Cria o diretório se ele não existir
                            }

                            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                                // Atualiza o caminho da imagem no banco
                                $sql = "UPDATE pessoas SET foto = :foto WHERE id_pessoa = :id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':foto', $uploadFilePath);
                                $stmt->bindParam(':id', $id_pessoa, PDO::PARAM_INT);
                                $stmt->execute();
                            } else {
                                $_SESSION['mensagem'] = "Erro ao mover o arquivo.";
                            }
                        } else {
                            $_SESSION['mensagem'] = "Formato de arquivo não permitido.";
                        }
                    }

                    // Verifica se é Pessoa Física ou Jurídica e cadastra os respectivos dados
                    if ($_POST['tipo_pessoa'] === 'fisica') {
                        $cpf = $_POST['cpf'];
                        $rg = $_POST['rg'];
                        cadastrarPessoaFisica($pdo, $cpf, $rg, $id_pessoa);
                        
                        $_SESSION['mensagem'] = "Pessoa física cadastrada com sucesso";
                        header('Location: ../../pages/cliente.php');
                        exit;

                    } elseif ($_POST['tipo_pessoa'] === 'juridica') {
                        $cnpj = $_POST['cnpj'];
                        $ie = $_POST['ie'];
                        $razao = $_POST['razao-social'];
                        $fantasia = $_POST['nome-fantasia'];
                        cadastrarPessoaJuridica($pdo, $cnpj, $ie, $razao, $fantasia, $id_pessoa);
                        $sucesso_juridica = true;

                        $_SESSION['mensagem'] = "Pessoa jurídica cadastrada com sucesso";
                        header('Location: ../../pages/cliente.php');
                        exit;
                    }

                }
            } else {
                $_SESSION['mensagem'] = "Senhas não coincidem.";
                header('Location: ../../pages/cliente.php');
                exit;
            }
        } else {
            $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios.";
            header('Location: ../../pages/cliente.php');
            exit;
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    header("Location: ../../pages/cliente.php");
    exit();
}
