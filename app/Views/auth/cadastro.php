<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimbastic Esports - Cadastro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Chakra Petch"', 'sans-serif'],
                        bebas: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-1: #081019;
            --bg-2: #132737;
            --accent: #10b981;
            --accent-2: #22d3ee;
            --card: rgba(10, 20, 32, 0.7);
            --border: rgba(255, 255, 255, 0.14);
        }

        body {
            background:
                radial-gradient(1100px 600px at 12% -8%, #1c3f58 0%, transparent 60%),
                radial-gradient(900px 500px at 90% 110%, #114b5f 0%, transparent 60%),
                linear-gradient(140deg, var(--bg-1), var(--bg-2));
            color: #f4f7fb;
            min-height: 100vh;
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

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 font-sans">
    <div class="noise"></div>

    <div class="w-full max-w-[480px] z-10 animate-fade-in">
        <main class="panel p-8 sm:p-10 text-center">
            
            <div class="font-bebas text-5xl tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 font-bold mb-1">
                PIMBASTIC ESPORTS
            </div>
            <p class="text-gray-400 text-sm mb-6 uppercase tracking-wider">Crie sua Conta</p>

            <div class="mb-5 bg-cyan-950/40 border border-cyan-800/30 text-cyan-200 p-3 rounded-xl text-left text-xs">
                O cadastro público cria apenas contas de <strong>cliente</strong>. Para criar admin, use o painel administrativo.
            </div>

            <!-- Error Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-300 p-4 rounded-xl text-left text-sm mb-6">
                    <span class="font-bold block mb-1">Erros de Validação:</span>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-red-200">
                        <?php if (is_array(session()->getFlashdata('error'))): ?>
                            <?php foreach (session()->getFlashdata('error') as $e): ?>
                                <li><?= esc($e) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><?= esc(session()->getFlashdata('error')) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/cadastro/salvar" class="space-y-4 text-left">
                <?= csrf_field() ?>
                
                <div>
                    <label for="nome" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Nome Completo</label>
                    <input type="text" id="nome" name="nome" value="<?= old('nome') ?>" required placeholder="ex: João Silva" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required placeholder="ex: joao@pimbastic.com" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none transition-colors">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="senha" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Senha</label>
                        <input type="password" id="senha" name="senha" required placeholder="Mínimo 6 caracteres" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:outline-none transition-colors">
                    </div>
                    <input type="hidden" name="perfil" value="cliente">
                    <div class="bg-cyan-950/40 border border-cyan-800/40 p-3 rounded-xl text-xs text-cyan-200">
                        Cadastros públicos são criados como <strong>cliente</strong>.
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold uppercase tracking-wider py-3.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-emerald-500/10 focus:outline-none">
                        Finalizar Cadastro
                    </button>
                </div>
            </form>

            <p class="mt-8 text-sm text-gray-400">
                Já tem uma conta? 
                <a href="/login" class="text-emerald-400 hover:text-cyan-400 font-semibold transition-colors duration-200">
                    Acesse o Login
                </a>
            </p>
        </main>
    </div>
</body>
</html>
