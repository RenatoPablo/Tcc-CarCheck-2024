<?php

//session_start();

require '../config/config.php';

try {
    //var_dump($_SESSION['id_pessoa']);
    $sql = "SELECT
                v.id_veiculo,
                v.placa,

                c.nome_cor,

                ti.nome_tipo_veiculo,

                mo.nome_modelo,

                ma.nome_marca
            FROM
                veiculos v
            LEFT JOIN
                cores c ON c.id_cor = v.fk_id_cor
            LEFT JOIN
                tipos_veiculos ti ON ti.id_tipo_veiculo = v.fk_id_tipo_veiculo
            LEFT JOIN
                modelos mo ON mo.id_modelo = v.fk_id_modelo
            LEFT JOIN
                marcas ma ON ma.id_marca = v.fk_id_marca
            LEFT JOIN
                pessoas p ON p.id_pessoa = v.fk_id_pessoa
            WHERE 
                p.id_pessoa = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_SESSION['id_pessoa']]);

        $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($veiculos);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>