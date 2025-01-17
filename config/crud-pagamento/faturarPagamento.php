<?php

session_start();

require '../config.php';

try {
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>