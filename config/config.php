<?php
// CONFIGURAÇÃO DO BANCO DE DADOS
$host = "localhost";
$dbname = "feles";
$user = "root";     // altere se necessário
$pass = "";         // altere se necessário

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // mostra erros
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // array associativo
            PDO::ATTR_EMULATE_PREPARES => false // prepared statements reais
        ]
    );

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
