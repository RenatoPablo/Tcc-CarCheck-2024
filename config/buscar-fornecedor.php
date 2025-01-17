<?php
    require '../config/config.php';

    // Verificar se o parâmetro 'query' foi enviado via GET
    if (isset($_GET['query'])) {
        $query = $_GET['query'];

        // Consulta ao banco de dados para buscar os fornecedores que correspondem à pesquisa
        $sql = "SELECT id_fornecedor, cnpj, nome_fantasia
                FROM fornecedores
                WHERE cnpj LIKE :query 
                LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();

        // Retornar os resultados como um array JSON
        $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($fornecedores);
    }
?>
