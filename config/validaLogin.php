<?php
session_start();
include('config.php');



// Verifica se o formulário foi enviado corretamente
if (!isset($_POST) OR empty($_POST['endereco_email']) OR empty($_POST['senha'])) {
    header("location: sair.php");
    exit();
}

// Captura os dados enviados pelo formulário
$email = $_POST['endereco_email'];
$senha_digitada = $_POST['senha'];
$lembrar_me = isset($_POST['lembrar_me']) ? $_POST['lembrar_me'] : 0; // Verifica se a opção 'Lembrar-me' foi marcada

// Consulta SQL para buscar o usuário pelo email
$sql = "SELECT * FROM pessoas WHERE endereco_email = :email";

try {
    $consulta = $pdo->prepare($sql);
    $consulta->execute(['email' => $email]);

    // Verifica se encontrou algum usuário com o email fornecido
    if ($consulta->rowCount() > 0) {
        $usuario = $consulta->fetch(PDO::FETCH_OBJ);

        // Verifica se a senha digitada corresponde ao hash no banco de dados
        if (password_verify($senha_digitada, $usuario->senha)) {
            // Define as variáveis de sessão após o login bem-sucedido
            $_SESSION['logado'] = true;
            $_SESSION['nomeUsuario'] = $usuario->nome_pessoa;
            $_SESSION['emailUsuario'] = $usuario->endereco_email;
            $_SESSION['permissao'] = $usuario->fk_id_permissao;
            $_SESSION['id_pessoa'] = $usuario->id_pessoa; // Adiciona o id_pessoa na sessão

            // Se a opção 'Lembrar-me' foi marcada, define um cookie com um token
            if ($lembrar_me) {
                $token = bin2hex(random_bytes(32)); // Gera um token seguro
                $expira = time() + (86400 * 30); // O cookie expira em 30 dias

                // Salva o token no banco de dados
                $sqlToken = "UPDATE pessoas SET token_login = :token WHERE endereco_email = :email";
                $stmtToken = $pdo->prepare($sqlToken);
                $stmtToken->execute(['token' => $token, 'email' => $email]);

                // Define o cookie com o token e o tempo de expiração
                setcookie('lembrar_me', $token, $expira, "/", "", false, true); // O cookie é seguro com o flag HttpOnly
            }

            // Redireciona o usuário de acordo com o nível de permissão
            if ($_SESSION['permissao'] == 3 || $_SESSION['permissao'] == 2) {
                header('location: ../pages/home-funci.php');
            } elseif ($_SESSION['permissao'] == 1) {
                header('location: ../pages/home-cliente.php');
            }
            exit();
        } else {
            // Senha incorreta
            echo "Usuário ou senha incorretos!<br>";
            echo "<a href='sair.php'>Voltar</a>";
        }
    } else {
        // Se o email não foi encontrado
        echo "Usuário ou senha incorretos!<br>";
        echo "<a href='sair.php'>Voltar</a>";
    }
} catch (PDOException $e) {
    // Captura e exibe qualquer erro de banco de dados
    echo "Erro ao realizar login: " . $e->getMessage();
}
