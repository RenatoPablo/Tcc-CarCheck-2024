<?php 

    //
    date_default_timezone_set("America/Sao_Paulo");

    $db = 'mysql:host=localhost;port=3306;dbname=carcheckdb';
   $usuario = 'root';
   $senha = '';

    //Define opções de conexão (Captura de Exception e seta o encode da conexão UTF8
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try {
        $pdo = new PDO($db, $usuario, $senha, $options);
    } catch (PDOException $e) {
        echo 'ERRO DE CONEXÃO: ' .$e->getMessage();
    }

    
?>