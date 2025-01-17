<?php
// Conectar ao banco de dados
require '../config.php'; // Substitua pelo caminho correto do seu arquivo de conexão

// Consultar as formas de pagamento
$sql = "SELECT * FROM formas_pagamento";
$stmt = $pdo->query($sql);

// Armazenar os resultados em um array
$formasPagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>