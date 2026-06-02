CREATE DATABASE IF NOT EXISTS pimbastic_esports;
USE pimbastic_esports;

CREATE TABLE IF NOT EXISTS campeonato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    pais VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS time (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tecnico VARCHAR(255) NOT NULL,
    sigla VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS jogo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campeonato_id INT NOT NULL,
    time_casa_id INT NOT NULL,
    time_fora_id INT NOT NULL,
    data_horario DATETIME NOT NULL,
    odd_casa DECIMAL(6,2) NOT NULL,
    odd_empate DECIMAL(6,2) NOT NULL,
    odd_fora DECIMAL(6,2) NOT NULL,
    status ENUM('agendado', 'liquidado') NOT NULL DEFAULT 'agendado',
    resultado_final ENUM('vitoria_casa', 'empate', 'vitoria_fora') DEFAULT NULL,
    CONSTRAINT fk_jogo_campeonato FOREIGN KEY (campeonato_id) REFERENCES campeonato(id) ON DELETE RESTRICT,
    CONSTRAINT fk_jogo_time_casa FOREIGN KEY (time_casa_id) REFERENCES time(id) ON DELETE RESTRICT,
    CONSTRAINT fk_jogo_time_fora FOREIGN KEY (time_fora_id) REFERENCES time(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    saldo_carteira DECIMAL(15,2) NOT NULL DEFAULT 0.00
);

CREATE TABLE IF NOT EXISTS aposta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    jogo_id INT NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    tipo_escolhido ENUM('vitoria_casa', 'empate', 'vitoria_fora') NOT NULL,
    odd_escolhida DECIMAL(6,2) NOT NULL,
    status ENUM('aberta', 'cancelada', 'vencida', 'perdida') NOT NULL DEFAULT 'aberta',
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_aposta_cliente FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE RESTRICT,
    CONSTRAINT fk_aposta_jogo FOREIGN KEY (jogo_id) REFERENCES jogo(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, 
    perfil ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    cliente_id INT UNIQUE, 
    CONSTRAINT fk_usuario_cliente FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE CASCADE
);

INSERT INTO campeonato (nome, pais) VALUES ('CBLOL 2026', 'Brasil');
INSERT INTO time (nome, tecnico, sigla) VALUES ('LOUD', 'Frost', 'LLL'), ('PaiN Gaming', 'Xero', 'PNG');
INSERT INTO jogo (campeonato_id, time_casa_id, time_fora_id, data_horario, odd_casa, odd_empate, odd_fora) VALUES (1, 1, 2, '2026-07-20 13:00:00', 1.85, 3.00, 2.10);
INSERT INTO cliente (nome, saldo_carteira) VALUES ('Admin Pimbastic', 5000.00);
INSERT INTO usuario (nome, email, senha, perfil, cliente_id) VALUES ('Admin', 'admin@pimbastic.com', 'admin123', 'admin', 1);
