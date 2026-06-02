<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-bebas text-3xl text-white">Debug — Liquidação de Apostas</h1>
        <p class="text-sm text-gray-400">Lista detalhada de jogos pendentes e apostas relacionadas (somente admin).</p>
    </div>
    <div>
        <a href="/admin/liquidacao" class="bg-white/5 hover:bg-white/10 text-gray-200 border border-white/10 px-3 py-2 rounded-xl text-sm">Voltar</a>
    </div>
</div>

<?php if (empty($jogos)): ?>
    <div class="cyber-card p-6 text-center">Nenhum jogo pendente encontrado.</div>
<?php else: ?>
    <?php foreach ($jogos as $jogo): ?>
        <div class="cyber-card p-6 mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-xs text-cyan-400 uppercase tracking-widest"><?= esc($jogo['campeonato'] ?? $jogo['campeonato']) ?></div>
                    <h2 class="text-xl font-bold text-white"><?= esc($jogo['casa'] ?? $jogo['casa']) ?> <span class="text-gray-400">vs</span> <?= esc($jogo['fora'] ?? $jogo['fora']) ?></h2>
                    <div class="text-sm text-gray-400">Data: <?= esc($jogo['data_horario']) ?> — Status: <?= esc($jogo['status'] ?? 'n/a') ?></div>
                </div>
                <div class="text-right text-sm text-gray-300">
                    Apostas abertas: <strong class="font-mono text-emerald-400"><?= esc($jogo['apostas_abertas'] ?? 0) ?></strong>
                </div>
            </div>

            <div class="mb-3">
                <h4 class="text-sm text-gray-300 font-semibold mb-2">Detalhes da partida (raw)</h4>
                <pre class="bg-black/40 p-3 rounded text-xs text-gray-200 overflow-auto"><?= esc(json_encode($jogo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            </div>

            <div>
                <h4 class="text-sm text-gray-300 font-semibold mb-2">Apostas relacionadas (<?= count($jogo['apostas'] ?? []) ?>)</h4>
                <?php if (empty($jogo['apostas'])): ?>
                    <div class="text-sm text-gray-400 italic">Nenhuma aposta encontrada para este jogo.</div>
                <?php else: ?>
                    <div class="overflow-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-xs text-gray-400 uppercase">
                                    <th class="px-3 py-2">ID</th>
                                    <th class="px-3 py-2">Cliente</th>
                                    <th class="px-3 py-2">Valor</th>
                                    <th class="px-3 py-2">Escolha</th>
                                    <th class="px-3 py-2">Odd</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2">Criado Em</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jogo['apostas'] as $a): ?>
                                    <tr class="border-t border-white/5">
                                        <td class="px-3 py-2 font-mono text-gray-200"><?= esc($a['id']) ?></td>
                                        <td class="px-3 py-2 text-gray-200"><?= esc($a['cliente_id']) ?></td>
                                        <td class="px-3 py-2 text-emerald-400 font-mono">R$ <?= number_format((float)$a['valor'], 2, ',', '.') ?></td>
                                        <td class="px-3 py-2 text-gray-200"><?= esc($a['tipo_escolhido']) ?></td>
                                        <td class="px-3 py-2 text-cyan-300 font-mono"><?= number_format((float)$a['odd_escolhida'], 2, ',', '.') ?></td>
                                        <td class="px-3 py-2 text-gray-200"><?= esc($a['status']) ?></td>
                                        <td class="px-3 py-2 text-gray-300"><?= esc($a['criado_em']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
