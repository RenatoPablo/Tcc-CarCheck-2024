<?php
require '../config.php';

try {
    // Prepara a consulta
    $sql = "SELECT 
            v.id_veiculo,
            v.placa,
            p.id_pessoa,
            p.nome_pessoa,
            c.id_cor,
            c.nome_cor,
            t.id_tipo_veiculo,
            t.nome_tipo_veiculo,
            mo.id_modelo,
            mo.nome_modelo,
            ma.id_marca,    
            ma.nome_marca
            FROM 
                veiculos v
            LEFT JOIN
                pessoas p ON p.id_pessoa = v.fk_id_pessoa
            LEFT JOIN
                cores c ON c.id_cor = v.fk_id_cor
            LEFT JOIN 
                tipos_veiculos t ON t.id_tipo_veiculo = v.fk_id_tipo_veiculo
            LEFT JOIN
                modelos mo ON mo.id_modelo = v.fk_id_modelo
            LEFT JOIN
                marcas ma ON ma.id_marca = v.fk_id_marca";
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados como JSON
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
