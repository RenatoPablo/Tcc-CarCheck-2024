<?php
// Conectar ao banco de dados
require '../config/config.php'; // Inclua aqui o arquivo que faz a conexão com o banco

if (isset($_GET['placa'])) {
    $placa = $_GET['placa'];

    // Consulta para buscar o veículo com base na placa
    $query = $pdo->prepare("
    SELECT  m.nome_modelo 
    FROM veiculos v 
    JOIN modelos m ON v.fk_id_modelo = m.id_modelo
    WHERE v.placa = :placa
    LIMIT 1");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $query->execute();

    // Verifica se encontrou algum veículo
    if ($query->rowCount() > 0) {
        $veiculo = $query->fetch(PDO::FETCH_ASSOC);
        echo $veiculo['nome_modelo']; // Retorna o nome do veículo
    } else {
        echo ''; // Se não encontrou a placa, retorna vazio
    }
}
?>
