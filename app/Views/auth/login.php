<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimbastic Esports - Login</title>
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

    <div class="w-full max-w-[440px] z-10 animate-fade-in">
        <main class="panel p-8 sm:p-10 text-center">
            
            <div class="font-bebas text-5xl tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 font-bold mb-1">
                PIMBASTIC ESPORTS
            </div>
            <p class="text-gray-400 text-sm mb-6 uppercase tracking-wider">Acesse sua Conta</p>

            <!-- Success Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 p-3 rounded-lg text-sm mb-6 text-left">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <!-- Error Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-300 p-3 rounded-lg text-sm mb-6 text-left">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Credentials guide for testing -->
            <div class="bg-cyan-950/40 border border-cyan-800/40 p-4 rounded-xl text-left text-xs mb-6 space-y-2">
                <span class="font-bold text-cyan-400 block uppercase tracking-wider">Dica para Avaliação:</span>
                <p class="text-gray-300">
                    • Para o painel de <strong>Administrador</strong>: use um e-mail com <code class="bg-cyan-900/60 text-white px-1 py-0.5 rounded font-mono">admin</code> (ex: <span class="text-cyan-300">admin@pimbastic.com</span>).
                </p>
                <p class="text-gray-300">
                    • Para o <strong>Sportsbook do Cliente</strong>: use qualquer outro e-mail (ex: <span class="text-cyan-300">cliente@pimbastic.com</span>).
                </p>
            </div>

            <form method="POST" action="/login/autenticar" class="space-y-5 text-left">
                <?= csrf_field() ?>
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="ex: admin@pimbastic.com" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label for="senha" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Digite sua senha" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold uppercase tracking-wider py-3.5 rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-emerald-500/10 focus:outline-none">
                    Entrar no Sistema
                </button>
            </form>

            <p class="mt-8 text-sm text-gray-400">
                Não tem uma conta? 
                <a href="/cadastro" class="text-emerald-400 hover:text-cyan-400 font-semibold transition-colors duration-200">
                    Cadastre-se
                </a>
            </p>
        </main>
    </div>
</body>
</html>
