<?php
session_start();

require_once dirname(__DIR__) . '/src/Infrastructure/Database/DatabaseConnector.php';
require_once dirname(__DIR__) . '/src/Infrastructure/Repositories/UsuarioRepository.php';
require_once dirname(__DIR__) . '/src/Application/Controllers/AuthController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\UsuarioRepository;
use PimbasticEsports\Application\Controllers\AuthController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $perfil = $_POST['perfil'] ?? 'cliente'; // Padrão é cliente

    $connector = new DatabaseConnector();
    $pdo = $connector->getConnection();
    
    $repo = new UsuarioRepository($pdo);
    $controller = new AuthController($repo);
    
    $controller->processarCadastro($nome, $email, $senha, $perfil);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Pimbastic Esports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        /* CSS REUTILIZADO DO SEU LOGIN.PHP */
        :root {
            --bg-1: #081019;
            --bg-2: #132737;
            --accent: #10b981;
            --accent-2: #22d3ee;
            --text: #f4f7fb;
            --muted: #b9c2cf;
            --card: rgba(10, 20, 32, 0.7);
            --border: rgba(255, 255, 255, 0.14);
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: "Chakra Petch", sans-serif;
            background: linear-gradient(140deg, var(--bg-1), var(--bg-2));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper { width: 100%; max-width: 420px; padding: 20px; }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            backdrop-filter: blur(10px);
            padding: 40px;
            text-align: center;
        }

        h1 { font-family: "Bebas Neue", sans-serif; font-size: 48px; margin: 0 0 10px; }

        .form-grid { display: grid; gap: 16px; text-align: left; }

        label { font-size: 14px; color: var(--muted); font-weight: 600; }

        input, select {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            padding: 12px 14px;
            font-family: "Chakra Petch", sans-serif;
            outline: none;
        }

        select option { background: #132737; color: white; }

        button {
            width: 100%;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #051220;
            font-weight: 700;
            padding: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <main class="panel">
            <h1>Criar Conta</h1>
            <p style="color: var(--muted); margin-bottom: 30px;">Registre-se na plataforma</p>

            <form method="POST" action="cadastro.php" class="form-grid">
                <div>
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" required placeholder="Digite seu nome">
                </div>
                
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="Digite seu e-mail">
                </div>

                <div>
                    <label for="perfil">Tipo de Conta</label>
                    <select id="perfil" name="perfil" required>
                        <option value="cliente">Cliente (Apostador)</option>
                        <option value="admin">Administrador (Gestor)</option>
                    </select>
                </div>
                
                <div>
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
                </div>

                <button type="submit">CADASTRAR E CONTINUAR</button>
            </form>

            <p style="margin-top: 25px; font-size: 14px; color: var(--muted);">
                Já tem conta? <a href="login.php" style="color: var(--accent); text-decoration: none;">Fazer Login</a>
            </p>
        </main>
    </div>
</body>
</html>