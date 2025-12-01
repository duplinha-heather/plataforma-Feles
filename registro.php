<?php
session_start();
require_once "config.php";

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    // ================================
    // VALIDAÇÃO
    // ================================
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        // Verifica se e-mail já existe
        $sql = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $sql->execute(['email' => $email]);

        if ($sql->rowCount() > 0) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            // ================================
            // REGISTRO
            // ================================
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $insert = $pdo->prepare("
                INSERT INTO users (nome, email, senha, xp, nivel, moedas)
                VALUES (:nome, :email, :senha, 0, 1, 0)
            ");

            $ok = $insert->execute([
                'nome'  => $nome,
                'email' => $email,
                'senha' => $hash
            ]);

            if ($ok) {
                // Loga automaticamente
                $_SESSION["user_id"] = $pdo->lastInsertId();
                header("Location: index.php");
                exit;
            } else {
                $erro = "Erro ao registrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Registrar - FELÉS</title>

<link rel="stylesheet" href="assets/css/registro.css">
</head>

<body>

<!-- Topo com logo -->
    <header>
        <div class="logo">FELES</div>
        <a href="login.php">
            <button class="btn-enter">Entrar</button>
        </a>
    </header>

<div class="login-container">
    
    <!-- LADO ESQUERDO (FORMULÁRIO) -->
    <div class="login-box">

        <h2>Crie sua conta:</h2>

        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="sucesso"><?= $sucesso ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <label class="label">Nome completo:</label>
            <input type="text" name="nome" class="input" placeholder="Seu nome">

            <label class="label">E-mail:</label>
            <input type="email" name="email" class="input" placeholder="Seu e-mail">

            <label class="label">Senha:</label>
            <input type="password" name="senha" class="input" placeholder="Crie uma senha">

            <button class="btn-submit">Registrar</button>
        </form>

    </div>

    <!-- LADO DIREITO -->
    <div class="welcome-box">
        Join us!
    </div>

</div>

    <footer>
        <div id="copyright">
            &#169
            2026
            FELES
        </div>
    </footer>

</body>
</html>
