<?php
session_start();

$erro = null;

// Lógica de Processamento do Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    /* * ==========================================
     * AQUI ENTRARÁ A SUA CONEXÃO COM O BANCO DE DADOS
     * ==========================================
     * Exemplo de como será no futuro:
     * $stmt = $pdo->prepare("SELECT id, nome, senha_hash, tipo_usuario FROM usuarios WHERE email = :email");
     * $stmt->execute(['email' => $email]);
     * $usuario = $stmt->fetch();
     * * if ($usuario && password_verify($senha, $usuario['senha_hash'])) { ... }
     */

    // MOCK (Dados fictícios para testar a lógica de redirecionamento)
    if ($email === 'admin@pimbastic.com' && $senha === 'admin') {
        
        // Criamos a sessão do Administrador
        $_SESSION['logado'] = true;
        $_SESSION['tipo_usuario'] = 'admin';
        
        // Redireciona para o painel do Admin (pode ser o seu index.php atual)
        header("Location: index.php"); 
        exit;

    } elseif ($email === 'cliente@teste.com' && $senha === 'cliente') {
        
        // Criamos a sessão do Cliente
        $_SESSION['logado'] = true;
        $_SESSION['tipo_usuario'] = 'cliente';
        
        // Redireciona para o painel do Cliente
        header("Location: painel_cliente.php"); 
        exit;

    } else {
        $erro = 'Credenciais inválidas. Tente novamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pimbastic Esports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        /* MANTENDO O SEU ESTILO ORIGINAL */
        :root {
            --bg-1: #081019;
            --bg-2: #132737;
            --accent: #10b981;
            --accent-2: #22d3ee;
            --text: #f4f7fb;
            --muted: #b9c2cf;
            --danger: #ef4444;
            --card: rgba(10, 20, 32, 0.7);
            --border: rgba(255, 255, 255, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: "Chakra Petch", sans-serif;
            background:
                radial-gradient(1100px 600px at 12% -8%, #1c3f58 0%, transparent 60%),
                radial-gradient(900px 500px at 90% 110%, #114b5f 0%, transparent 60%),
                linear-gradient(140deg, var(--bg-1), var(--bg-2));
            overflow-x: hidden;
            /* Adicionado para centralizar o formulário na tela */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.08;
            background-image:
                linear-gradient(0deg, rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 3px 3px, 3px 3px;
            mix-blend-mode: soft-light;
            z-index: 1;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            padding: 20px;
            animation: rise 700ms ease-out both;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            padding: 40px;
            text-align: center;
        }

        h1 {
            font-family: "Bebas Neue", sans-serif;
            margin: 0 0 10px;
            font-size: 48px;
            letter-spacing: 0.03em;
        }

        .subtitle {
            margin: 0 0 30px;
            color: var(--muted);
            font-size: 16px;
        }

        .form-grid {
            display: grid;
            gap: 16px;
            text-align: left;
        }

        label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 600;
        }

        input {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            padding: 12px 14px;
            font-family: "Chakra Petch", sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 200ms;
        }

        input:focus {
            border-color: var(--accent-2);
            background: rgba(255, 255, 255, 0.08);
        }

        button {
            width: 100%;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #051220;
            font-weight: 700;
            font-size: 16px;
            padding: 14px;
            margin-top: 10px;
            letter-spacing: 0.03em;
            transition: transform 180ms ease, filter 180ms ease;
            font-family: "Chakra Petch", sans-serif;
        }

        button:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .feedback-error {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.45);
            color: #fecaca;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: left;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="noise"></div>

    <div class="login-wrapper">
        <main class="panel">
            <h1>Pimbastic Login</h1>
            <p class="subtitle">Acesse o painel do sistema</p>

            <?php if ($erro !== null): ?>
                <div class="feedback-error">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="form-grid">
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="Digite seu e-mail">
                </div>
                
                <div>
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
                </div>

                <button type="submit">ENTRAR NO SISTEMA</button>
            </form>
        </main>
    </div>
</body>
</html>