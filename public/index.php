<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Infrastructure/Database/DatabaseConnector.php';
require_once dirname(__DIR__) . '/src/Application/Forms/FormsService.php';

use PimbasticEsports\Application\Forms\FormsService;
use PimbasticEsports\Infrastructure\Database\DatabaseConnector;

$feedbackType = isset($_GET['feedback_type']) ? (string) $_GET['feedback_type'] : null;
$feedbackMessage = isset($_GET['feedback_message']) ? (string) $_GET['feedback_message'] : null;

$dbStatus = 'offline';
$databaseTime = null;
$isOnline = false;

$campeonatos = [];
$times = [];
$clientes = [];
$jogos = [];

try {
    $connector = new DatabaseConnector();
    $pdo = $connector->getConnection();
    $formsService = new FormsService($pdo);
    $result = $pdo->query('SELECT NOW() AS server_time')->fetch();

    $dbStatus = 'online';
    $databaseTime = $result['server_time'] ?? null;
    $isOnline = true;

    $formsService->handleSubmission($_SERVER, $_POST);
    $viewData = $formsService->fetchViewData();
    $campeonatos = $viewData['campeonatos'];
    $times = $viewData['times'];
    $clientes = $viewData['clientes'];
    $jogos = $viewData['jogos'];
} catch (Throwable $e) {
    $dbStatus = 'offline';
    $databaseTime = null;
    $isOnline = false;

    if ($feedbackMessage === null) {
        $feedbackType = 'error';
        $feedbackMessage = 'Banco de dados indisponivel. Verifique o ambiente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimbastic Esports</title>
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
        }

        .container {
            width: min(1140px, 92vw);
            margin: 0 auto;
            padding: 40px 0 56px;
            position: relative;
            z-index: 2;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 28px;
            align-items: stretch;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }

        .hero-main {
            padding: 34px;
            animation: rise 700ms ease-out both;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 13px;
            letter-spacing: 0.03em;
            color: var(--muted);
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: <?= $isOnline ? '"var(--accent)"' : '"var(--danger)"' ?>;
            box-shadow: 0 0 12px <?= $isOnline ? 'rgba(16, 185, 129, 0.9)' : 'rgba(239, 68, 68, 0.8)' ?>;
        }

        h1 {
            font-family: "Bebas Neue", sans-serif;
            margin: 18px 0 12px;
            font-size: clamp(44px, 8vw, 82px);
            letter-spacing: 0.03em;
            line-height: 0.95;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-size: clamp(16px, 2.3vw, 20px);
            max-width: 60ch;
        }

        .hero-side {
            padding: 30px;
            display: grid;
            gap: 14px;
            animation: rise 900ms ease-out both;
        }

        .stat {
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.02);
        }

        .label {
            margin: 0 0 6px;
            color: var(--muted);
            font-size: 13px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .value {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .status-online {
            color: var(--accent);
        }

        .status-offline {
            color: var(--danger);
        }

        .grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .card {
            padding: 18px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            animation: rise 1s ease-out both;
        }

        .card:nth-child(2) {
            animation-delay: 120ms;
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.45;
            font-size: 15px;
        }

        .btn-link {
            display: inline-block;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            margin: 10px 0;
            transition: color 200ms ease;
        }

        .btn-link:hover {
            color: var(--accent-2);
        }

        .list-small {
            margin-top: 8px;
            font-size: 13px;
            max-height: 100px;
            overflow-y: auto;
        }

        .list-item {
            color: var(--muted);
            padding: 3px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
            margin-top: 26px;
            opacity: 0.8;
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .feedback {
            margin-top: 20px;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: 14px;
            animation: rise 650ms ease-out both;
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

        .forms-wrap {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-panel {
            padding: 20px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.03);
        }

        .form-panel h3 {
            margin: 0 0 12px;
            font-size: 19px;
        }

        .form-grid {
            display: grid;
            gap: 10px;
        }

        label {
            font-size: 13px;
            color: var(--muted);
        }

        input,
        select,
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

        select option {
            color: #0b1220;
        }

        button {
            border: none;
            cursor: pointer;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #051220;
            font-weight: 700;
            letter-spacing: 0.03em;
            transition: transform 180ms ease, filter 180ms ease;
        }

        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .forms-wrap {
                grid-template-columns: 1fr;
            }

            .container {
                padding-top: 24px;
            }

            h1 {
                font-size: clamp(42px, 16vw, 68px);
            }
        }
    </style>
</head>
<body>
    <div class="noise"></div>

    <main class="container">
        <section class="hero">
            <article class="panel hero-main">
                <span class="tag">
                    <span class="dot"></span>
                    Plataforma ativa
                </span>
                <h1>Pimbastic Esports</h1>
                <p class="subtitle">
                    Cadastre campeonatos, jogos, clientes e apostas em um fluxo simples para demonstracao do sistema.
                </p>
                <p class="brand">Built for high-intensity matches</p>
            </article>

            <aside class="panel hero-side">
                <div class="stat">
                    <p class="label">Status do Banco</p>
                    <p class="value <?= $isOnline ? 'status-online' : 'status-offline' ?>">
                        <?= $isOnline ? 'ONLINE' : 'OFFLINE' ?>
                    </p>
                </div>

                <div class="stat">
                    <p class="label">Horario do Servidor</p>
                    <p class="value">
                        <?= $databaseTime !== null ? htmlspecialchars((string) $databaseTime, ENT_QUOTES, 'UTF-8') : '--' ?>
                    </p>
                </div>
            </aside>
        </section>

        <?php if ($feedbackMessage !== null): ?>
            <section class="feedback <?= $feedbackType === 'success' ? 'feedback-success' : 'feedback-error' ?>">
                <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8') ?>
            </section>
        <?php endif; ?>

        <section class="grid">
            <article class="card">
                <h3>Campeonatos</h3>
                <p><?= count($campeonatos) ?> campeonato(s) cadastrado(s)</p>
                <a href="formularios/campeonato.php" class="btn-link">Novo Campeonato →</a>
                <div class="list-small">
                    <?php foreach ($campeonatos as $c): ?>
                        <div class="list-item">• <?= htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            </article>

            <article class="card">
                <h3>Times</h3>
                <p><?= count($times) ?> time(s) cadastrado(s)</p>
                <a href="formularios/time.php" class="btn-link">Novo Time →</a>
                <div class="list-small">
                    <?php foreach ($times as $t): ?>
                        <div class="list-item">• <?= htmlspecialchars($t['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            </article>

            <article class="card">
                <h3>Clientes</h3>
                <p><?= count($clientes) ?> cliente(s) cadastrado(s)</p>
                <a href="formularios/cliente.php" class="btn-link">Novo Cliente →</a>
                <div class="list-small">
                    <?php foreach ($clientes as $cl): ?>
                        <div class="list-item">• <?= htmlspecialchars($cl['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            </article>

            <article class="card">
                <h3>Jogos</h3>
                <p><?= count($jogos) ?> jogo(s) cadastrado(s)</p>
                <a href="formularios/jogo.php" class="btn-link">Novo Jogo →</a>
            </article>

            <article class="card">
                <h3>Apostas</h3>
                <p>Registre apostas com cliente, odd e status</p>
                <a href="formularios/aposta.php" class="btn-link">Nova Aposta →</a>
            </article>
        </section>
    </main>
</body>
</html>