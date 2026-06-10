<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada | Pimbastic Esports</title>
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
        body {
            background:
                radial-gradient(1100px 600px at 12% -8%, #1c3f58 0%, transparent 60%),
                radial-gradient(900px 500px at 90% 110%, #114b5f 0%, transparent 60%),
                linear-gradient(140deg, #081019, #132737);
            color: #f4f7fb;
            min-height: 100vh;
        }
        .glitch {
            animation: glitch 2s infinite;
        }
        @keyframes glitch {
            0%, 90%, 100% { transform: translate(0); }
            92% { transform: translate(-4px, 2px); }
            94% { transform: translate(4px, -2px); }
            96% { transform: translate(-2px, -4px); }
            98% { transform: translate(2px, 4px); }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 font-sans">
    <div class="text-center max-w-lg mx-auto">
        <!-- Glitch 404 -->
        <div class="font-bebas text-[10rem] leading-none tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 glitch select-none">
            404
        </div>

        <h1 class="text-2xl font-bold text-white mb-3 uppercase tracking-wider">
            Rota Não Encontrada
        </h1>
        <p class="text-gray-400 mb-8 text-sm leading-relaxed">
            A página que você está tentando acessar não existe no sistema, 
            foi movida ou você não tem permissão para visualizá-la.
        </p>

        <!-- Action buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold uppercase tracking-wider py-3 px-6 rounded-xl transition-all duration-200 hover:-translate-y-0.5 shadow-lg shadow-emerald-500/10 text-sm">
                Voltar ao Início
            </a>
            <button onclick="history.back()" class="bg-white/5 hover:bg-white/10 text-gray-300 border border-white/10 font-semibold uppercase tracking-wider py-3 px-6 rounded-xl transition-all duration-200 text-sm">
                Página Anterior
            </button>
        </div>

        <!-- System info -->
        <div class="mt-12 text-xs text-gray-600 font-mono">
            Pimbastic Esports v2.0 &middot; CodeIgniter 4
        </div>
    </div>
</body>
</html>
