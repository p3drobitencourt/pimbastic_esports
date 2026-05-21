<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<!-- Dashboard Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Painel Administrativo</h1>
        <p class="text-xs text-emerald-400 uppercase tracking-widest">Painel de gerenciamento do sistema</p>
    </div>
    
    <!-- System status badge -->
    <div class="cyber-card px-4 py-2.5 flex items-center gap-3 border-l-4 border-cyan-400 shadow-lg shadow-cyan-500/5">
        <span class="flex h-2.5 w-2.5 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
        </span>
        <div class="text-xs font-semibold text-gray-300 font-mono">
            Status do Banco: <span class="text-emerald-400 font-bold uppercase tracking-wider"><?= esc($server_status) ?></span>
        </div>
    </div>
</div>

<!-- Metrics Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="cyber-card p-6 border-l-4 border-emerald-500 shadow-md shadow-emerald-500/5">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Usuários Ativos</div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-bold font-mono text-white"><?= esc($metricas['usuarios_ativos']) ?></span>
            <span class="text-xs text-emerald-500 font-semibold flex items-center">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                +12%
            </span>
        </div>
        <p class="text-[11px] text-gray-500 mt-2">Contas com sessões ativas nas últimas 24h</p>
    </div>

    <div class="cyber-card p-6 border-l-4 border-amber-500 shadow-md shadow-amber-500/5">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Apostas Pendentes (Liquidação)</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc($metricas['apostas_pendentes']) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Apostas aguardando encerramento dos jogos</p>
    </div>

    <div class="cyber-card p-6 border-l-4 border-cyan-500 shadow-md shadow-cyan-500/5">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Volume Financeiro</div>
        <div class="text-4xl font-bold font-mono text-cyan-400">R$ <?= number_format($metricas['volume_financeiro'], 2, ',', '.') ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Volume de transações ativas nesta semana</p>
    </div>
</div>

<!-- Entities Overviews -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Left: Latest Championships -->
    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                Últimos Campeonatos
            </h3>
            <a href="/admin/campeonatos/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">
                Novo Cadastro
            </a>
        </div>
        
        <div class="space-y-3">
            <?php foreach ($campeonatos as $c): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div class="font-semibold text-sm text-gray-200"><?= esc($c['nome']) ?></div>
                    <div class="text-xs text-gray-500 font-mono"><?= esc($c['pais']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/campeonatos" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Campeonatos
        </a>
    </div>

    <!-- Right: Latest Teams -->
    <div class="cyber-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-white uppercase tracking-wider text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Últimos Times
            </h3>
            <a href="/admin/times/create" class="bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-200">
                Novo Cadastro
            </a>
        </div>
        
        <div class="space-y-3">
            <?php foreach ($times as $t): ?>
                <div class="flex justify-between items-center p-3 rounded-xl bg-black/25 border border-white/5 hover:border-white/10 transition-colors">
                    <div class="font-semibold text-sm text-gray-200"><?= esc($t['nome']) ?></div>
                    <div class="text-xs px-2 py-0.5 bg-slate-800 rounded font-mono text-cyan-400 border border-white/5"><?= esc($t['sigla']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/times" class="block w-full text-center mt-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 font-semibold text-sm transition-all duration-200">
            Gerenciar Times
        </a>
    </div>

</div>

<!-- Recent activity table -->
<div class="cyber-card p-6">
    <h3 class="font-bold text-white uppercase tracking-wider text-base mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Atividade Recente do Sistema
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="pb-3 px-4">Entidade</th>
                    <th class="pb-3 px-4">Nome do Registro</th>
                    <th class="pb-3 px-4">Data de Modificação</th>
                    <th class="pb-3 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimos_cadastros as $cadastro): ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-4 font-semibold text-cyan-400"><?= esc($cadastro['tipo']) ?></td>
                        <td class="py-3 px-4 font-bold text-white"><?= esc($cadastro['nome']) ?></td>
                        <td class="py-3 px-4 text-gray-400 font-mono text-xs"><?= esc($cadastro['data']) ?></td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-emerald-950/50 text-emerald-400 border border-emerald-500/30">Processado</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
