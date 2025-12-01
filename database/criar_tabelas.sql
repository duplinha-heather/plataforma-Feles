-- ============================================================
-- BANCO DE DADOS FELÉS – Plataforma de Estudos Gamificada
-- ============================================================

CREATE DATABASE IF NOT EXISTS feles_platform;
USE feles_platform;

-- ============================================================
-- 1. Tabela de Usuários
-- ============================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default.png',
    xp INT DEFAULT 0,
    nivel INT DEFAULT 1,
    moedas INT DEFAULT 0,
    streak INT DEFAULT 0,
    ultimo_dia_streak DATE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 2. Cursos
-- ============================================================

CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 3. Aulas
-- ============================================================

CREATE TABLE aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    conteudo TEXT,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- ============================================================
-- 4. Exercícios
-- ============================================================

CREATE TABLE exercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aula_id INT NOT NULL,
    pergunta TEXT NOT NULL,
    tipo ENUM('multipla', 'vf', 'lacuna', 'arrastar') NOT NULL,
    resposta_correta TEXT NOT NULL,
    alternativas JSON,
    dificuldade ENUM('facil','medio','dificil') DEFAULT 'medio',
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);

-- ============================================================
-- 5. Respostas dos Usuários
-- ============================================================

CREATE TABLE usuarios_exercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercicio_id INT NOT NULL,
    acertou BOOLEAN,
    pontuacao INT DEFAULT 0,
    data_resposta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE
);

-- ============================================================
-- 6. Badges
-- ============================================================

CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    icone VARCHAR(255),
    condicao VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 7. Badges conquistadas pelos usuários
-- ============================================================

CREATE TABLE usuarios_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    data_conquista TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
);

-- ============================================================
-- 8. Ranking semanal
-- ============================================================

CREATE TABLE ranking_semanal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ano INT NOT NULL,
    semana INT NOT NULL,
    xp_semana INT DEFAULT 0,
    liga ENUM('Bronze','Prata','Ouro','Platina','Diamante') DEFAULT 'Bronze',
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 9. Loja (itens compráveis)
-- ============================================================

CREATE TABLE loja_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    tipo ENUM('avatar','tema','booster','streak_protection') NOT NULL,
    preco INT NOT NULL,
    icone VARCHAR(255),
    descricao TEXT
);

-- ============================================================
-- 10. Inventário do Usuário
-- ============================================================

CREATE TABLE usuarios_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    data_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES loja_itens(id) ON DELETE CASCADE
);

-- ============================================================
-- 11. Histórico de atividades
-- ============================================================

CREATE TABLE atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    descricao TEXT,
    xp_ganho INT DEFAULT 0,
    data_atividade TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 12. Metas diárias e semanais
-- ============================================================

CREATE TABLE metas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipo ENUM('diaria','semanal') NOT NULL,
    descricao VARCHAR(255),
    objetivo INT DEFAULT 1,
    progresso INT DEFAULT 0,
    concluida BOOLEAN DEFAULT FALSE,
    data_referencia DATE NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 13. Controle de streak
-- ============================================================

CREATE TABLE streak_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    data DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 14. Log de XP
-- ============================================================

CREATE TABLE xp_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quantidade INT NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 15. Progresso por aula
-- ============================================================

CREATE TABLE usuarios_aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    aula_id INT NOT NULL,
    concluida BOOLEAN DEFAULT FALSE,
    data_conclusao TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE
);

-- ============================================================
-- 16. Desafios (relâmpago/semanal/especial)
-- ============================================================

CREATE TABLE desafios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    tipo ENUM('relampago','semanal','especial') NOT NULL,
    xp_recompensa INT NOT NULL,
    tempo_limite INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 17. Progresso dos desafios
-- ============================================================

CREATE TABLE usuarios_desafios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    desafio_id INT NOT NULL,
    concluido BOOLEAN DEFAULT FALSE,
    data_conclusao TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (desafio_id) REFERENCES desafios(id) ON DELETE CASCADE
);
