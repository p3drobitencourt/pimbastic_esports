<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Liquidação de Apostas</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Processe resultados e atualize os saldos automaticamente</p>
    </div>

    <a href="/admin/dashboard" class="bg-white/5 hover:bg-white/10 text-gray-200 border border-white/10 px-4 py-2 rounded-xl text-sm font-semibold uppercase tracking-wider transition-colors duration-200">
        Voltar ao Dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="cyber-card p-6 border-l-4 border-emerald-500">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Jogos Pendentes</div>
        <div class="text-4xl font-bold font-mono text-white"><?= esc(count($jogos)) ?></div>
        <p class="text-[11px] text-gray-500 mt-2">Aguardando resultado final</p>
    </div>

    <div class="cyber-card p-6 lg:col-span-2">
        <h3 class="font-bold text-white uppercase tracking-wider text-base mb-3">Como funciona</h3>
        <ul class="space-y-2 text-sm text-gray-300">
            <li>• Selecione o jogo pendente.</li>
            <li>• Informe o resultado final real.</li>
            <li>• O sistema credita o saldo das apostas vencedoras em transação.</li>
        </ul>
    </div>
</div>

<?php if (empty($jogos)): ?>
    <div class="cyber-card p-8 text-center border border-white/10">
        <div class="text-lg font-semibold text-white">Nenhum jogo pendente de liquidação.</div>
        <p class="text-sm text-gray-400 mt-2">Assim que houver partidas encerradas com apostas abertas, elas aparecerão aqui.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <?php foreach ($jogos as $jogo): ?>
            <div class="cyber-card p-6 border border-white/10 hover:border-emerald-400/30 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                    <div>
                        <div class="text-xs uppercase tracking-widest text-cyan-400 font-semibold"><?= esc($jogo['campeonato']) ?></div>
                        <h2 class="text-2xl font-bold text-white mt-1"><?= esc($jogo['casa']) ?> <span class="text-gray-400">vs</span> <?= esc($jogo['fora']) ?></h2>
                        <div class="text-sm text-gray-400 mt-2">Data: <?= esc(date('d/m/Y H:i', strtotime($jogo['data_horario']))) ?></div>
                    </div>

                    <div class="text-right">
                        <div class="text-xs text-gray-500 uppercase tracking-widest">Apostas abertas</div>
                        <div class="text-3xl font-bold text-emerald-400 font-mono"><?= esc($jogo['apostas_abertas']) ?></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-5 text-center text-sm">
                    <div class="rounded-xl bg-black/25 border border-white/5 p-3">
                        <div class="text-xs text-gray-500 uppercase tracking-widest mb-1">Casa</div>
                        <div class="font-bold text-white">x<?= esc(number_format((float) $jogo['odd_casa'], 2, ',', '.')) ?></div>
                    </div>
                    <div class="rounded-xl bg-black/25 border border-white/5 p-3">
                        <div class="text-xs text-gray-500 uppercase tracking-widest mb-1">Empate</div>
                        <div class="font-bold text-white">x<?= esc(number_format((float) $jogo['odd_empate'], 2, ',', '.')) ?></div>
                    </div>
                    <div class="rounded-xl bg-black/25 border border-white/5 p-3">
                        <div class="text-xs text-gray-500 uppercase tracking-widest mb-1">Fora</div>
                        <div class="font-bold text-white">x<?= esc(number_format((float) $jogo['odd_fora'], 2, ',', '.')) ?></div>
                    </div>
                </div>

                <?php if (($usuario_logado['perfil'] ?? '') === 'admin'): ?>
                <form action="/admin/liquidacao/processar/<?= esc($jogo['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label for="resultado_<?= esc($jogo['id']) ?>" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Resultado final</label>
                        <select id="resultado_<?= esc($jogo['id']) ?>" name="resultado" class="w-full bg-black/40 border border-white/10 focus:border-emerald-400 rounded-xl px-4 py-3 text-white focus:outline-none transition-colors appearance-none cursor-pointer" required>
                            <option value="">Selecione...</option>
                            <option value="vitoria_casa">Vitória da casa</option>
                            <option value="empate">Empate</option>
                            <option value="vitoria_fora">Vitória do visitante</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
                            Liquidar jogo
                        </button>
                    </div>
                </form>
                <?php else: ?>
                    <div class="text-sm text-gray-400 italic">Apenas administradores podem processar liquidações.</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>