<?php
// ============================================================
// HEADER.PHP – Carregado em todas as páginas protegidas
// ============================================================

session_start();

// ---------------------------------------
// 1. Verifica se usuário está logado
// ---------------------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config.php";

// ---------------------------------------
// 2. Carrega dados do usuário
// ---------------------------------------
$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("SELECT nome, avatar, xp, nivel, moedas, streak 
                        FROM users 
                        WHERE id = :id");
$query->execute(['id' => $user_id]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    // Usuário não encontrado (algo deu errado)
    session_destroy();
    header("Location: login.php");
    exit;
}

// Dados do usuário
$nome       = $usuario['nome'];
$avatar     = $usuario['avatar'];
$xp         = $usuario['xp'];
$nivel      = $usuario['nivel'];
$moedas     = $usuario['moedas'];
$streak     = $usuario['streak'];

?>

<!-- ============================================================
     INÍCIO DO HEADER (HTML)
     ============================================================ -->

<header class="header">
    <div class="container">

        <!-- ------------------------------
             LOGO DA PLATAFORMA
        ---------------------------------->
        <div class="logo">
            <a href="index.php">
                <img src="assets/img/logo.png" alt="Felés" height="48">
            </a>
        </div>

        <!-- ------------------------------
             MENU DE NAVEGAÇÃO
        ---------------------------------->
        <nav class="menu">
            <ul>
                <li><a href="cursos.php">Cursos</a></li>
                <li><a href="desafios.php">Desafios</a></li>
                <li><a href="ranking.php">Ranking</a></li>
                <li><a href="loja.php">Loja</a></li>
                <li><a href="perfil.php">Perfil</a></li>
            </ul>
        </nav>

        <!-- ------------------------------
             ÁREA DO USUÁRIO
        ---------------------------------->
        <div class="user-info">

            <div class="user-stats">
                <span class="stat">
                    🔥 <strong><?= $streak ?></strong> dias
                </span>

                <span class="stat">
                    ⭐ XP: <strong><?= $xp ?></strong>
                </span>

                <span class="stat">
                    🏅 Nível <strong><?= $nivel ?></strong>
                </span>

                <span class="stat">
                    🪙 <strong><?= $moedas ?></strong>
                </span>
            </div>

            <!-- Avatar + menu de dropdown -->
            <div class="user-avatar">
                <img src="uploads/avatars/<?= htmlspecialchars($avatar) ?>"
                     alt="Avatar"
                     class="avatar">

                <div class="dropdown">
                    <a href="perfil.php">Meu Perfil</a>
                    <a href="configuracoes.php">Configurações</a>
                    <a href="logout.php">Sair</a>
                </div>
            </div>

        </div>

    </div>
</header>

<!-- Estilos rápidos (depois pode mover para CSS separado) -->
<style>
    .header {
        background: #ffffff;
        border-bottom: 1px solid #ddd;
        padding: 12px 0;
    }
    .header .container {
        width: 90%;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .menu ul {
        list-style: none;
        display: flex;
        gap: 20px;
    }
    .menu a {
        text-decoration: none;
        font-weight: bold;
        color: #333;
    }
    .user-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .user-stats .stat {
        margin-right: 10px;
        font-size: 14px;
    }
    .user-avatar {
        position: relative;
        cursor: pointer;
    }
    .user-avatar .avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid #444;
    }
    .user-avatar .dropdown {
        display: none;
        position: absolute;
        top: 50px;
        right: 0;
        background: white;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 150px;
    }
    .user-avatar:hover .dropdown {
        display: block;
    }
    .dropdown a {
        display: block;
        padding: 6px;
        text-decoration: none;
        color: #333;
    }
    .dropdown a:hover {
        background: #f2f2f2;
    }
</style>
