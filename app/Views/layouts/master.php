<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pimbastic Esports') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Chakra Petch"', 'sans-serif'],
                        bebas: ['"Bebas Neue"', 'sans-serif'],
                    },
                    colors: {
                        cyber: {
                            bg: '#081019',
                            card: 'rgba(19, 39, 55, 0.5)',
                            border: 'rgba(255, 255, 255, 0.1)',
                            accent: '#10b981', // Emerald
                            accent2: '#22d3ee', // Cyan
                            danger: '#ef4444',
                        }
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
            --card: rgba(19, 39, 55, 0.5);
            --border: rgba(255, 255, 255, 0.1);
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
            opacity: 0.04;
            background-image:
                linear-gradient(0deg, rgba(255,255,255,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 4px 4px, 4px 4px;
            mix-blend-mode: soft-light;
            z-index: 1;
        }

        .cyber-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cyber-card:hover {
            border-color: rgba(34, 211, 238, 0.3);
            box-shadow: 0 15px 35px rgba(34, 211, 238, 0.05);
        }

        .glow-accent {
            text-shadow: 0 0 12px rgba(16, 185, 129, 0.6);
        }

        .glow-cyan {
            text-shadow: 0 0 12px rgba(34, 211, 238, 0.6);
        }

        /* Customize inputs for cyber vibe */
        .cyber-input {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border);
            color: #fff;
            transition: all 0.2s ease;
        }
        .cyber-input:focus {
            border-color: var(--accent-2);
            box-shadow: 0 0 0 2px rgba(34, 211, 238, 0.2);
            outline: none;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #081019;
        }
        ::-webkit-scrollbar-thumb {
            background: #1c3f58;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #22d3ee;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen font-sans">
    <div class="noise"></div>

    <!-- Header bar -->
    <header class="sticky top-0 z-40 w-full bg-cyber-bg/85 border-b border-white/10 backdrop-blur-md px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <button id="menu-toggle" class="md:hidden text-gray-400 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="font-bebas text-3xl tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 font-bold hover:scale-105 transition-transform duration-200">
                PIMBASTIC ESPORTS
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- User Status Info -->
            <div class="hidden sm:flex flex-col items-end text-right">
                <span class="text-xs text-gray-400 uppercase tracking-widest">Sessão</span>
                <span class="text-sm font-semibold text-cyan-400">
                    <?php if (session()->get('usuario_nome')): ?>
                        <?= esc(session()->get('usuario_nome')) ?>
                        <span class="text-xs bg-cyan-900/50 text-cyan-300 border border-cyan-500/30 px-2 py-0.5 rounded ml-1 uppercase">
                            <?= esc(session()->get('usuario_perfil')) ?>
                        </span>
                    <?php else: ?>
                        Visitante
                    <?php endif; ?>
                </span>
            </div>

            <div class="h-8 w-px bg-white/10"></div>
            
            <a href="/logout" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Sair
            </a>
        </div>
    </header>

    <div class="flex flex-grow flex-col md:flex-row relative">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed md:sticky top-[73px] left-0 z-30 w-64 h-[calc(100vh-73px)] bg-cyber-bg/95 border-r border-white/5 p-6 flex flex-col justify-between -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:block">
            <div class="space-y-6">
                <!-- Navigation Groups -->

                <?php if (session()->get('usuario_perfil') === 'cliente'): ?>
                <!-- === MENU DO CLIENTE === -->
                <div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold mb-3 px-3">Navegação Cliente</div>
                    <nav class="space-y-1">
                        <a href="/cliente/sportsbook" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('cliente*') ? 'bg-cyan-500/10 text-cyan-400 border-l-2 border-cyan-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Sportsbook</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>

                <?php if (session()->get('usuario_perfil') === 'admin'): ?>
                <!-- === MENU DO ADMINISTRADOR === -->
                <div>
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold mb-3 px-3">Administrativo</div>
                    <nav class="space-y-1">
                        <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('admin/dashboard') ? 'bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="/admin/campeonatos" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('admin/campeonatos*') ? 'bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <span>Campeonatos</span>
                        </a>
                        <a href="/admin/times" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('admin/times*') ? 'bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Times</span>
                        </a>
                        <a href="/admin/jogos" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('admin/jogos*') ? 'bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            <span>Jogos</span>
                        </a>
                        <a href="/admin/usuarios" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition-all group <?= url_is('admin/usuarios*') ? 'bg-emerald-500/10 text-emerald-400 border-l-2 border-emerald-400 font-semibold' : '' ?>">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Usuários</span>
                        </a>
                    </nav>
                </div>
                <?php endif; ?>
            </div>

            <!-- Footer of Sidebar -->
            <div class="border-t border-white/5 pt-4 text-xs text-gray-500">
                Pimbastic v2.0 - CI4
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow p-4 md:p-8 overflow-y-auto">
            <!-- Global Flash Messages -->
            <?php if (session()->getFlashdata('success') || session()->getFlashdata('sucesso')): ?>
                <div class="mb-6 flex items-center gap-3 bg-emerald-500/15 border border-emerald-500/35 text-emerald-300 p-4 rounded-xl shadow-lg shadow-emerald-500/5 animate-pulse">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="font-bold">Sucesso!</span> <?= esc(session()->getFlashdata('success') ?: session()->getFlashdata('sucesso')) ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error') || session()->getFlashdata('erros') || session()->getFlashdata('error_message')): ?>
                <div class="mb-6 bg-red-500/15 border border-red-500/35 text-red-300 p-4 rounded-xl shadow-lg shadow-red-500/5">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="font-bold">Aviso/Erro!</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1 pl-1">
                        <?php 
                            $errors = session()->getFlashdata('error') ?: session()->getFlashdata('erros') ?: session()->getFlashdata('error_message');
                            if (is_array($errors)):
                                foreach ($errors as $error):
                        ?>
                                    <li><?= esc($error) ?></li>
                        <?php 
                                endforeach;
                            else:
                        ?>
                                <li><?= esc($errors) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- View content section -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Toggle script for mobile sidebar -->
    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar when clicking outside of it on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    </script>
</body>
</html>
