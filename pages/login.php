<?php
session_start();
include('../config/config.php');

// Verifica se o cookie de "lembrar_me" existe
if (isset($_COOKIE['lembrar_me'])) {
    // Obtém o token do cookie
    $token = $_COOKIE['lembrar_me'];

    // Busca o token no banco de dados
    $sql = "SELECT * FROM pessoas WHERE token_login = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_OBJ);

    // Se o token for válido, loga o usuário automaticamente
    if ($usuario) {
        // Define as variáveis de sessão
        $_SESSION['logado'] = true;
        $_SESSION['nomeUsuario'] = $usuario->nome_pessoa;
        $_SESSION['emailUsuario'] = $usuario->endereco_email;
        $_SESSION['permissao'] = $usuario->fk_id_permissao;
        $_SESSION['id_pessoa'] = $usuario->id_pessoa; // Adiciona o id_pessoa na sessão

        // Redireciona o usuário de acordo com o nível de permissão
        if ($_SESSION['permissao'] == 3 || $_SESSION['permissao'] == 2) {
            header('location: ../pages/home-funci.php');
            exit();
        } elseif ($_SESSION['permissao'] == 1) {
            header('location: ../pages/home-cliente.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/botao.css">
    
    <title>Login-CarCheck</title>
</head>
<body>
    <div class="container-esquerdo">
        <div class="texto-esquerdo">
            <h1>Olá,<br> Seja Bem Vindo!</h1>
            <p>Faça Login e obtenha acesso ao nosso sistema.</p>
        </div>
    </div>

    <div class="container-direito">
        <div class="dados">
            <i class="fa-regular fa-user fa-2xl usuario" style="color: #0d3587;"></i>

            <form action="../config/validaLogin.php" method="POST" class="form-login">
                <input class="input" type="text"  name="endereco_email" required placeholder="Digite seu Email">
                <br>
                <input class="input" type="password"  name="senha" required placeholder="Digite sua Senha">
                
                <label class="label-lembrar-me">
                    <input type="checkbox" name="lembrar_me" value="1">
                    <p>Lembrar-me</p>
                    
                </label>
                
                <button type="submit" name="submit">
                    Entrar
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                    </div>
                </button>

            </form>
        </div>
    </div>
</body>
</html>
