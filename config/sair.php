<?php
session_start();

// Destroi todas as variáveis de sessão
session_destroy(); 

// Remove o cookie de "lembrar_me", se existir
if (isset($_COOKIE['lembrar_me'])) {
    setcookie('lembrar_me', '', time() - 3600, '/'); // Define o tempo de expiração no passado para remover o cookie
}

// Redireciona para a página de login
header('Location: ../pages/login.php');
exit();
?>
