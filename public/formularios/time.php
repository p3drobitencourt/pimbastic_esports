<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Infrastructure/Database/DatabaseConnector.php';
require_once dirname(__DIR__, 2) . '/src/Application/Forms/FormsService.php';

use PimbasticEsports\Application\Forms\FormsService;
use PimbasticEsports\Infrastructure\Database\DatabaseConnector;

$feedbackType = isset($_GET['feedback_type']) ? (string) $_GET['feedback_type'] : null;
$feedbackMessage = isset($_GET['feedback_message']) ? (string) $_GET['feedback_message'] : null;

try {
    $connector = new DatabaseConnector();
    $pdo = $connector->getConnection();
    $formsService = new FormsService($pdo);
    
    $formsService->handleSubmission($_SERVER, $_POST);
} catch (Throwable $e) {
    if ($feedbackMessage === null) {
        $feedbackType = 'error';
        $feedbackMessage = 'Banco de dados indisponivel.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Time - Pimbastic Esports</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
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
        }

        .container {
            width: min(700px, 90vw);
            margin: 40px auto;
            position: relative;
            z-index: 2;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            padding: 32px;
        }

        h1 {
            font-family: "Bebas Neue", sans-serif;
            margin: 0 0 8px;
            font-size: 36px;
            letter-spacing: 0.03em;
        }

        .subtitle {
            color: var(--muted);
            font-size: 14px;
            margin: 0 0 24px;
        }

        .form-grid {
            display: grid;
            gap: 14px;
        }

        label {
            font-size: 13px;
            color: var(--muted);
            display: block;
            margin-bottom: 4px;
        }

        input,
        button {
            width: 100%;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            padding: 10px 12px;
            font-family: "Chakra Petch", sans-serif;
            font-size: 14px;
        }

        button {
            border: none;
            cursor: pointer;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #051220;
            font-weight: 700;
            letter-spacing: 0.03em;
            transition: transform 180ms ease, filter 180ms ease;
            margin-top: 14px;
        }

        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
        }

        .feedback {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .feedback-success {
            background: rgba(16, 185, 129, 0.12);
            border-color: rgba(16, 185, 129, 0.45);
            color: #bbf7d0;
        }

        .feedback-error {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.45);
            color: #fecaca;
        }

        .back-link {
            display: inline-block;
            color: var(--accent);
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 16px;
            transition: color 200ms ease;
        }

        .back-link:hover {
            color: var(--accent-2);
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="panel">
            <a href="../index.php" class="back-link">← Voltar para Dashboard</a>

            <h1>Novo Time</h1>
            <p class="subtitle">Cadastre um novo time para participar dos campeonatos</p>

            <?php if ($feedbackMessage !== null): ?>
                <div class="feedback <?= $feedbackType === 'success' ? 'feedback-success' : 'feedback-error' ?>">
                    <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form-grid">
                <input type="hidden" name="form_type" value="time">

                <div>
                    <label for="nome">Nome do Time *</label>
                    <input id="nome" name="nome" type="text" required>
                </div>

                <div>
                    <label for="tecnico">Técnico *</label>
                    <input id="tecnico" name="tecnico" type="text" required>
                </div>

                <div>
                    <label for="sigla">Sigla (opcional)</label>
                    <input id="sigla" name="sigla" type="text" placeholder="Ex: FLA, VAS">
                </div>

                <button type="submit">Cadastrar Time</button>
            </form>
        </div>
    </main>
</body>
</html>