<?php
session_start();
require_once "config.php";

// ============================================================
// PROCESSAMENTO DO LOGIN
// ============================================================

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $erro = "Digite seu e-mail.";
    } else {
        // Busca usuário pelo e-mail
        $sql = $pdo->prepare("SELECT id, senha FROM users WHERE email = :email");
        $sql->execute(['email' => $email]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Aqui, apenas e-mail. Caso use senha no futuro:
            // if (password_verify($_POST['senha'], $user['senha'])) {}
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        } else {
            $erro = "E-mail não encontrado.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - FELÉS</title>

<style>
    /* ============================
       ESTILO PRINCIPAL
       Baseado no layout da imagem
       ============================ */
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

    .google-btn {
        background: #6ab6c1;
        color: black;
        border: none;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-size: 16px;
        cursor: pointer;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .label-email {
        font-weight: bold;
        margin-top: 20px;
        display: block;
        font-size: 16px;
    }

    .input-email {
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        border: none;
        margin-top: 5px;
        margin-bottom: 12px;
        background: #a3d5d6;
    }

    .btn-login {
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

    /* Erro */
    .erro {
        background: #ffb5b5;
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
    <button class="btn-enter">Entrar</button>
</div>

<div class="login-container">
    
    <!-- LADO ESQUERDO (FORMULÁRIO) -->
    <div class="login-box">

        <h2>Entre com sua conta:</h2>

        <?php if ($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <!-- Botão Google (estrutura pronta para integrar SDK depois) -->
        <button class="google-btn">
            <img src="assets/img/google-icon.png" width="22">
            Continuar com o Google
        </button>

        <p style="text-align:center; margin:10px 0; font-size:14px;">Ou entrar com e-mail</p>

        <!-- Formulário -->
        <form method="POST">

            <label class="label-email">E-mail:</label>
            <input type="email" name="email" class="input-email" placeholder="Seu e-mail">

            <button class="btn-login">Entrar</button>

        </form>

    </div>

    <!-- LADO DIREITO -->
    <div class="welcome-box">
        Bem-vindo!
    </div>

</div>

</body>
</html>
