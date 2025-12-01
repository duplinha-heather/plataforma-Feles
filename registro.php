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

<style>
    body {
        background: #f7f8da;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* Topbar */
    .topbar {
        background: #b24bb0;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .topbar .logo {
        font-size: 36px;
        font-weight: bold;
        color: #6ad0e3;
        letter-spacing: 2px;
    }

    .topbar .btn-enter {
        background: #f0ffb4;
        padding: 6px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    /* Container geral */
    .login-container {
        margin: 40px auto;
        width: 80%;
        display: flex;
        justify-content: center;
        align-items: stretch;
        border-radius: 18px;
    }

    /* Área do formulário */
    .login-box {
        background: #dbb4dd;
        padding: 40px;
        width: 50%;
        border-radius: 18px 0 0 18px;
    }

    .login-box h2 {
        margin-bottom: 20px;
        font-size: 22px;
        text-align: center;
    }

    .input {
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        border: none;
        background: #a3d5d6;
        margin-bottom: 12px;
    }

    .label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
        font-size: 16px;
    }

    .btn-submit {
        background: #f0ffb4;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        margin-top: 10px;
        font-size: 16px;
    }

    /* Painel lateral */
    .welcome-box {
        background: #62a3b8;
        width: 50%;
        border-radius: 0 18px 18px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #b636a8;
        font-size: 40px;
        font-weight: bold;
    }

    /* Erro e sucesso */
    .erro {
        background: #ffb5b5;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        text-align: center;
        font-weight: bold;
    }

    .sucesso {
        background: #b1ffb5;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        text-align: center;
        font-weight: bold;
    }
</style>
</head>

<body>

<!-- Topo com logo -->
<div class="topbar">
    <div class="logo">FELES</div>
    <a href="login.php">
        <button class="btn-enter">Entrar</button>
    </a>
</div>

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

</body>
</html>
