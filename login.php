<?php
session_start();
require_once "config/config.php";

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
<title>Login - FELES</title>

<link rel="stylesheet" href="assets/css/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <div class="logo">FELES</div>
        <a href="registro.php">
            <button class="btn-enter">Cadastrar</button>
        </a>
    </header>

    <div class="login-container">
    
    <!-- LADO ESQUERDO (FORMULÁRIO) -->
        <div class="login-box">

            <h2>Entre com sua conta:</h2>

            <?php if ($erro): ?>
                <div class="erro"><?= $erro ?></div>
            <?php endif; ?>

            <button class="google-btn">
                <i class="fa-brands fa-google"></i>
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

    <footer>
        <div id="copyright">
            &#169
            2026
            FELES
        </div>
    </footer>

</body>
</html>
