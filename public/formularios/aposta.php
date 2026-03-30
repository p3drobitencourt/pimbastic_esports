<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/src/Infrastructure/Database/DatabaseConnector.php';
require_once dirname(__DIR__, 2) . '/src/Application/Forms/FormsService.php';

use PimbasticEsports\Application\Forms\FormsService;
use PimbasticEsports\Infrastructure\Database\DatabaseConnector;

$feedbackType = isset($_GET['feedback_type']) ? (string) $_GET['feedback_type'] : null;
$feedbackMessage = isset($_GET['feedback_message']) ? (string) $_GET['feedback_message'] : null;

$clientes = [];
$jogos = [];

try {
    $connector = new DatabaseConnector();
    $pdo = $connector->getConnection();
    $formsService = new FormsService($pdo);
    
    $formsService->handleSubmission($_SERVER, $_POST);
    $viewData = $formsService->fetchViewData();
    $clientes = $viewData['clientes'];
    $jogos = $viewData['jogos'];
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
    <title>Nova Aposta - Pimbastic Esports</title>
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

        input:read-only {
            opacity: 0.7;
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

            <h1>Nova Aposta</h1>
            <p class="subtitle">Coloque sua aposta em um jogo e escolha o resultado</p>

            <?php if ($feedbackMessage !== null): ?>
                <div class="feedback <?= $feedbackType === 'success' ? 'feedback-success' : 'feedback-error' ?>">
                    <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form-grid">
                <input type="hidden" name="form_type" value="aposta">

                <div>
                    <label for="cliente_id">Cliente *</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Selecione um cliente</option>
                        <?php foreach ($clientes as $cli): ?>
                            <option value="<?= $cli['id'] ?>">
                                <?= htmlspecialchars($cli['nome'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="jogo_id">Jogo *</label>
                    <select id="jogo_id" name="jogo_id" required onchange="preencherOdds(event)">
                        <option value="">Selecione um jogo</option>
                        <?php foreach ($jogos as $jogo): ?>
                            <option 
                                value="<?= $jogo['id'] ?>"
                                data-odd-casa="<?= $jogo['odd_casa'] ?>"
                                data-odd-empate="<?= $jogo['odd_empate'] ?>"
                                data-odd-fora="<?= $jogo['odd_fora'] ?>"
                            >
                                <?= htmlspecialchars($jogo['time_casa_nome'], ENT_QUOTES, 'UTF-8') ?> 
                                vs 
                                <?= htmlspecialchars($jogo['time_fora_nome'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="valor">Valor da Aposta (R$) *</label>
                    <input id="valor" name="valor" type="number" step="0.01" min="0.01" required>
                </div>

                <div>
                    <label for="tipo_escolhido">Tipo de Aposta *</label>
                    <select id="tipo_escolhido" name="tipo_escolhido" required onchange="preencherOddEscolhida()">
                        <option value="">Selecione o resultado</option>
                        <option value="vitoria_casa">Vitória Time Casa</option>
                        <option value="empate">Empate</option>
                        <option value="vitoria_fora">Vitória Time Fora</option>
                    </select>
                </div>

                <div>
                    <label for="odd_escolhida">Odd Escolhida</label>
                    <input id="odd_escolhida" name="odd_escolhida" type="number" step="0.01" min="1" readonly>
                </div>

                <button type="submit">Registrar Aposta</button>
            </form>
        </div>
    </main>

    <script>
        const storedOdds = {
            vitoria_casa: 0,
            empate: 0,
            vitoria_fora: 0
        };

        function preencherOdds(event) {
            const option = event.target.selectedOptions[0];
            storedOdds.vitoria_casa = parseFloat(option.dataset.oddCasa) || 0;
            storedOdds.empate = parseFloat(option.dataset.oddEmpate) || 0;
            storedOdds.vitoria_fora = parseFloat(option.dataset.oddFora) || 0;
            preencherOddEscolhida();
        }

        function preencherOddEscolhida() {
            const tipoEscolhido = document.getElementById('tipo_escolhido').value;
            const oddEscolhida = document.getElementById('odd_escolhida');
            
            if (tipoEscolhido === 'vitoria_casa') {
                oddEscolhida.value = storedOdds.vitoria_casa;
            } else if (tipoEscolhido === 'empate') {
                oddEscolhida.value = storedOdds.empate;
            } else if (tipoEscolhido === 'vitoria_fora') {
                oddEscolhida.value = storedOdds.vitoria_fora;
            } else {
                oddEscolhida.value = '';
            }
        }
    </script>
</body>
</html>