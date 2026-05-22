<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Painel Administrativo</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Central de gestão do sistema</p>
    </div>

    <div class="cyber-card px-4 py-2.5 flex items-center gap-3 border-l-4 border-cyan-400 shadow-lg shadow-cyan-500/5">
        <span class="flex h-2.5 w-2.5 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
        </span>
        <div class="text-xs font-semibold text-gray-300 font-mono">
            Status do Sistema: <span class="text-emerald-400 font-bold uppercase tracking-wider"><?= esc($server_status) ?></span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6 mb-10">
    <div class="cyber-card p-6 border-l-4 border-emerald-500">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Campeonatos</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['campeonatos_total']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Cadastros disponíveis</p>
    </div>

    <div class="cyber-card p-6 border-l-4 border-cyan-500">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Times</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['times_total']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Elencos e organizações</p>
    </div>

    <div class="cyber-card p-6 border-l-4 border-amber-500">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Jogos</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['jogos_total']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Confrontos cadastrados</p>
    </div>

    <div class="cyber-card p-6 border-l-4 border-fuchsia-500">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Usuários</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['usuarios_total']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Contas de acesso</p>
    </div>

    <a href="/admin/liquidacao" class="cyber-card p-6 border-l-4 border-emerald-400 hover:border-emerald-300 transition-colors block">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Liquidações</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['liquidacoes_pendentes']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Jogos prontos para processamento</p>
    </a>
</div>

<div class="cyber-card p-6 mb-8 border border-cyan-500/20">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Cadastro rápido</h3>
            <p class="text-xs text-gray-400 mt-1">Use estes atalhos para criar usuários com o perfil certo desde o início.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/admin/usuarios/create?perfil=admin" class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors">
                Novo Admin
            </a>
            <a href="/admin/usuarios/create?perfil=cliente" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors">
                Novo Cliente
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Atalhos de CRUD</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="/admin/campeonatos" class="rounded-xl border border-white/10 bg-black/25 p-4 hover:border-cyan-400/40 transition-colors">
                <div class="text-sm font-bold text-white">Campeonatos</div>
                <div class="text-xs text-gray-400 mt-1">Listar, criar, editar e excluir</div>
            </a>
            <a href="/admin/times" class="rounded-xl border border-white/10 bg-black/25 p-4 hover:border-cyan-400/40 transition-colors">
                <div class="text-sm font-bold text-white">Times</div>
                <div class="text-xs text-gray-400 mt-1">Gestão completa de elencos</div>
            </a>
            <a href="/admin/jogos" class="rounded-xl border border-white/10 bg-black/25 p-4 hover:border-cyan-400/40 transition-colors">
                <div class="text-sm font-bold text-white">Jogos</div>
                <div class="text-xs text-gray-400 mt-1">Confrontos, odds e datas</div>
            </a>
            <a href="/admin/usuarios" class="rounded-xl border border-white/10 bg-black/25 p-4 hover:border-cyan-400/40 transition-colors">
                <div class="text-sm font-bold text-white">Usuários</div>
                <div class="text-xs text-gray-400 mt-1">Perfis e acessos</div>
            </a>
            <a href="/admin/liquidacao" class="rounded-xl border border-white/10 bg-black/25 p-4 hover:border-emerald-400/40 transition-colors">
                <div class="text-sm font-bold text-white">Liquidação</div>
                <div class="text-xs text-gray-400 mt-1">Processar resultados e creditar saldos</div>
            </a>
        </div>
    </div>

    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Últimos cadastros</h3>
        </div>

        <div class="space-y-3">
            <?php foreach ($ultimos_cadastros as $cadastro): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5">
                    <div>
                        <div class="font-semibold text-sm text-gray-200"><?= esc($cadastro['tipo']) ?></div>
                        <div class="text-xs text-gray-500"><?= esc($cadastro['nome']) ?></div>
                    </div>
                    <div class="text-xs text-gray-400 font-mono"><?= esc($cadastro['data']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Campeonatos recentes</h3>
            <a href="/admin/campeonatos/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">Novo</a>
        </div>

        <div class="space-y-3">
            <?php foreach ($campeonatos as $c): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div class="font-semibold text-sm text-gray-200"><?= esc($c['nome']) ?></div>
                    <div class="text-xs text-gray-500 font-mono"><?= esc($c['pais'] ?: 'Global') ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/campeonatos" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Campeonatos
        </a>
    </div>

    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Times recentes</h3>
            <a href="/admin/times/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">Novo</a>
        </div>

        <div class="space-y-3">
            <?php foreach ($times as $t): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div class="font-semibold text-sm text-gray-200"><?= esc($t['nome']) ?></div>
                    <div class="text-xs px-2 py-0.5 bg-slate-800 rounded font-mono text-cyan-400 border border-white/5"><?= esc($t['sigla'] ?: '-') ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/times" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Times
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Jogos recentes</h3>
            <a href="/admin/jogos/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">Novo</a>
        </div>

        <div class="space-y-3">
            <?php foreach ($jogos as $jogo): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div>
                        <div class="font-semibold text-sm text-gray-200"><?= esc($jogo['campeonato']) ?></div>
                        <div class="text-xs text-gray-500"><?= esc($jogo['casa']) ?> vs <?= esc($jogo['fora']) ?></div>
                    </div>
                    <div class="text-xs text-gray-400 font-mono"><?= esc(date('d/m H:i', strtotime($jogo['data_horario']))) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/jogos" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Jogos
        </a>
    </div>

    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base">Usuários recentes</h3>
            <a href="/admin/usuarios/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">Novo</a>
        </div>

        <div class="space-y-3">
            <?php foreach ($usuarios as $usuario): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div>
                        <div class="font-semibold text-sm text-gray-200"><?= esc($usuario['nome']) ?></div>
                        <div class="text-xs text-gray-500"><?= esc($usuario['email']) ?></div>
                    </div>
                    <div class="text-xs px-2 py-0.5 rounded font-mono text-cyan-400 border border-white/5 bg-slate-800"><?= esc($usuario['perfil']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/usuarios" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Usuários
        </a>
    </div>
</div>
<?= $this->endSection() ?>
