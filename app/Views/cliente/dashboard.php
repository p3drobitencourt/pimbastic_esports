<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<?php
    $saldoAtual = $saldo_realtime ?? ($cliente['saldo_carteira'] ?? 0);
?>

<div class="flex flex-col gap-6 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="font-bebas text-4xl tracking-wider text-white">Dashboard do Cliente</h1>
            <p class="text-xs text-cyan-400 uppercase tracking-widest">Carteira, apostas, cancelamento e histórico em tempo real</p>
        </div>

        <div class="cyber-card px-6 py-4 border-l-4 border-emerald-400 min-w-[220px]">
            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Saldo em tempo real</span>
            <div class="text-3xl font-bold text-emerald-400 font-mono">R$ <?= number_format((float) $saldoAtual, 2, ',', '.') ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Apostas abertas</div>
            <div class="text-3xl font-bold text-white font-mono"><?= esc($resumo['abertas'] ?? 0) ?></div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Vencidas</div>
            <div class="text-3xl font-bold text-emerald-400 font-mono"><?= esc($resumo['vencidas'] ?? 0) ?></div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Perdidas</div>
            <div class="text-3xl font-bold text-red-400 font-mono"><?= esc($resumo['perdidas'] ?? 0) ?></div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Carteira</div>
            <div class="text-3xl font-bold text-cyan-400 font-mono">R$ <?= number_format((float) $saldoAtual, 2, ',', '.') ?></div>
        </div>
    </div>
</div>

<div class="cyber-card p-6 mb-8">
    <h3 class="text-lg font-bold text-white mb-3">Adicionar Saldo</h3>
    <p class="text-sm text-gray-400 mb-4">Você pode creditar saldo manualmente para testar o fluxo de apostas. Esse valor é simulado e não integra gateway de pagamento.</p>
    <form method="POST" action="/cliente/carteira/adicionar" class="flex flex-wrap gap-3 items-center">
        <?= csrf_field() ?>
        <input name="valor" type="number" step="0.01" min="1" placeholder="Valor em R$" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white w-48">
        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-gray-900 font-bold px-4 py-2 rounded-xl text-sm">Adicionar</button>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 cyber-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Jogos ativos</h2>
            <span class="text-xs text-gray-400 uppercase tracking-widest"><?= count($jogos ?? []) ?> disponíveis</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <?php foreach ($jogos as $jogo): ?>
                <div class="bg-black/25 border border-white/5 rounded-2xl p-4 flex flex-col gap-4">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-[10px] px-2 py-1 rounded bg-cyan-950/60 text-cyan-300 border border-cyan-500/20 uppercase tracking-wider"><?= esc($jogo['camp_nome']) ?></span>
                        <span class="text-[10px] text-gray-500 font-mono"><?= date('d/m H:i', strtotime($jogo['data_horario'])) ?></span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-sm">
                        <div class="bg-slate-900/60 rounded-xl p-2">
                            <div class="text-gray-400 text-[10px] uppercase">Casa</div>
                            <div class="font-bold text-emerald-400"><?= esc($jogo['casa']) ?></div>
                            <div class="font-mono text-white"><?= number_format((float) $jogo['odd_casa'], 2) ?></div>
                        </div>
                        <div class="bg-slate-900/60 rounded-xl p-2">
                            <div class="text-gray-400 text-[10px] uppercase">Empate</div>
                            <div class="font-bold text-cyan-400">X</div>
                            <div class="font-mono text-white"><?= number_format((float) $jogo['odd_empate'], 2) ?></div>
                        </div>
                        <div class="bg-slate-900/60 rounded-xl p-2">
                            <div class="text-gray-400 text-[10px] uppercase">Fora</div>
                            <div class="font-bold text-emerald-400"><?= esc($jogo['fora']) ?></div>
                            <div class="font-mono text-white"><?= number_format((float) $jogo['odd_fora'], 2) ?></div>
                        </div>
                    </div>

                    <form method="POST" action="/cliente/apostar" class="space-y-3">
                        <?= csrf_field() ?>
                        <input type="hidden" name="jogo_id" value="<?= esc($jogo['id']) ?>">
                        <div class="grid grid-cols-3 gap-2">
                            <button name="tipo" value="casa" class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase tracking-wider text-emerald-300">Casa</button>
                            <button name="tipo" value="empate" class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase tracking-wider text-cyan-300">Empate</button>
                            <button name="tipo" value="fora" class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase tracking-wider text-emerald-300">Fora</button>
                        </div>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" min="1" name="valor" placeholder="Valor" class="flex-1 bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white">
                            <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-gray-950 font-bold uppercase tracking-wider text-sm">Apostar</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="cyber-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Carteira</h2>
            <span class="text-xs text-emerald-400 uppercase tracking-widest">Atualizada</span>
        </div>

        <div class="space-y-3">
            <div class="bg-black/25 rounded-xl p-4 border border-white/5">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Saldo atual</div>
                <div class="text-3xl font-bold text-emerald-400 font-mono">R$ <?= number_format((float) $saldoAtual, 2, ',', '.') ?></div>
            </div>
            <div class="bg-black/25 rounded-xl p-4 border border-white/5">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Saldo da sessão</div>
                <div class="text-lg font-semibold text-white"><?= esc($usuario_logado['nome'] ?? 'Cliente') ?></div>
                <div class="text-xs text-gray-400">Perfil: <?= esc($usuario_logado['perfil'] ?? 'cliente') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="cyber-card p-6 mb-8">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Minhas apostas</h2>
        <span class="text-xs text-gray-400 uppercase tracking-widest">Histórico completo</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="py-3 px-4">Jogo</th>
                    <th class="py-3 px-4">Valor</th>
                    <th class="py-3 px-4">Odd</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apostas as $aposta): ?>
                    <tr class="border-b border-white/5">
                        <td class="py-4 px-4 text-white font-semibold"><?= esc($aposta['campeonato']) ?> - <?= esc($aposta['casa']) ?> x <?= esc($aposta['fora']) ?></td>
                        <td class="py-4 px-4 text-emerald-400 font-mono">R$ <?= number_format((float) $aposta['valor'], 2, ',', '.') ?></td>
                        <td class="py-4 px-4 text-cyan-400 font-mono"><?= number_format((float) $aposta['odd_escolhida'], 2) ?></td>
                        <td class="py-4 px-4">
                            <?php if ($aposta['status'] === 'aberta'): ?>
                                <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-amber-950/50 text-amber-400 border border-amber-500/30">Aberta</span>
                            <?php elseif ($aposta['status'] === 'cancelada'): ?>
                                <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-slate-800 text-slate-300 border border-white/10">Cancelada</span>
                            <?php elseif ($aposta['status'] === 'vencida'): ?>
                                <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-emerald-950/50 text-emerald-400 border border-emerald-500/30">Vencida</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-red-950/50 text-red-400 border border-red-500/30">Perdida</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php if ($aposta['status'] === 'aberta'): ?>
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="/cliente/cancelar-aposta/<?= esc($aposta['id']) ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-red-500/10 text-red-300 border border-red-500/20 text-xs uppercase tracking-wider">Cancelar</button>
                                    </form>

                                    <?php
                                        $tipoAtual = 'fora';
                                        if ($aposta['tipo_escolhido'] === 'vitoria_casa') {
                                            $tipoAtual = 'casa';
                                        } elseif ($aposta['tipo_escolhido'] === 'empate') {
                                            $tipoAtual = 'empate';
                                        }
                                    ?>
                                    <form method="POST" action="/cliente/atualizar-aposta/<?= esc($aposta['id']) ?>" class="flex items-center gap-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="jogo_id" value="<?= esc($aposta['jogo_id']) ?>">
                                        <select name="tipo" class="bg-black/40 border border-white/10 rounded-lg px-2 py-2 text-xs text-white">
                                            <option value="casa" <?= $tipoAtual === 'casa' ? 'selected' : '' ?>>Casa</option>
                                            <option value="empate" <?= $tipoAtual === 'empate' ? 'selected' : '' ?>>Empate</option>
                                            <option value="fora" <?= $tipoAtual === 'fora' ? 'selected' : '' ?>>Fora</option>
                                        </select>
                                        <input type="number" step="0.01" min="1" name="valor" value="<?= esc($aposta['valor']) ?>" class="w-24 bg-black/40 border border-white/10 rounded-lg px-2 py-2 text-xs text-white">
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-cyan-500/10 text-cyan-300 border border-cyan-500/20 text-xs uppercase tracking-wider">Atualizar</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-gray-500">Sem ações disponíveis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="cyber-card p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Histórico completo</h2>
        <span class="text-xs text-gray-400 uppercase tracking-widest">Ordens e liquidações</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="py-3 px-4">Data</th>
                    <th class="py-3 px-4">Confronto</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Valor</th>
                    <th class="py-3 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $item): ?>
                    <tr class="border-b border-white/5">
                        <td class="py-4 px-4 text-gray-400 font-mono text-xs"><?= esc(date('d/m/Y H:i', strtotime($item['criado_em']))) ?></td>
                        <td class="py-4 px-4 text-white"><?= esc($item['campeonato']) ?> - <?= esc($item['casa']) ?> x <?= esc($item['fora']) ?></td>
                        <td class="py-4 px-4 text-gray-300">
                            <?php if ($item['tipo_escolhido'] === 'vitoria_casa'): ?>Casa
                            <?php elseif ($item['tipo_escolhido'] === 'vitoria_fora'): ?>Fora
                            <?php else: ?>Empate
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 text-emerald-400 font-mono">R$ <?= number_format((float) $item['valor'], 2, ',', '.') ?></td>
                        <td class="py-4 px-4">
                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-slate-800 text-slate-200 border border-white/10"><?= esc($item['status']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>