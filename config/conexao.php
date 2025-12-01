<?php
// CONFIGURAÇÕES DO BANCO DE DADOS
$host = "localhost";      // servidor local
$usuario = "root";        // usuário do MySQL
$senha = "";              // senha do MySQL (alterar se necessário)
$banco = "feles_platform"; // nome do banco

// CRIA A CONEXÃO
$conn = new mysqli($host, $usuario, $senha, $banco);

// VERIFICA ERROS
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Forçar UTF-8 (evita erro com acentos)
$conn->set_charset("utf8");

?>
