<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Jogos / Confrontos</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Lista de partidas cadastradas no sistema</p>
    </div>
    
    <?php if (($usuario_logado['perfil'] ?? '') === 'admin'): ?>
    <a href="/admin/jogos/create" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none flex items-center gap-1.5 shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Novo Jogo
    </a>
    <?php endif; ?>
</div>

<div class="cyber-card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="pb-3 px-4">ID</th>
                    <th class="pb-3 px-4">Campeonato</th>
                    <th class="pb-3 px-4">Confronto</th>
                    <th class="pb-3 px-4">Data/Horário</th>
                    <th class="pb-3 px-4 text-center">Odds (C / E / F)</th>
                    <th class="pb-3 px-4 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jogos as $j): ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-4 font-mono text-cyan-400 font-semibold">#<?= esc($j['id']) ?></td>
                        <td class="py-4 px-4">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-cyan-950/65 text-cyan-300 border border-cyan-500/20"><?= esc($j['campeonato']) ?></span>
                        </td>
                        <td class="py-4 px-4 font-bold text-white">
                            <?= esc($j['casa']) ?> <span class="text-gray-500 font-normal text-xs">vs</span> <?= esc($j['fora']) ?>
                        </td>
                        <td class="py-4 px-4 text-gray-300 font-mono text-xs"><?= date('d/m/Y H:i', strtotime($j['data_horario'])) ?></td>
                        <td class="py-4 px-4 text-center font-mono text-xs">
                            <span class="text-emerald-400 font-bold"><?= number_format($j['odd_casa'], 2) ?></span>
                            <span class="text-gray-500 mx-1">/</span>
                            <span class="text-cyan-400 font-bold"><?= number_format($j['odd_empate'], 2) ?></span>
                            <span class="text-gray-500 mx-1">/</span>
                            <span class="text-emerald-400 font-bold"><?= number_format($j['odd_fora'], 2) ?></span>
                        </td>
                        <td class="py-4 px-4 text-center space-x-2">
                            <?php if (($usuario_logado['perfil'] ?? '') === 'admin'): ?>
                                <a href="/admin/jogos/edit/<?= $j['id'] ?>" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-3 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors inline-block">
                                    Editar
                                </a>
                                <form action="/admin/jogos/delete/<?= $j['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('Excluir este jogo?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 px-3 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors">
                                        Excluir
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Sem ações disponíveis</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pager)): ?>
        <div class="mt-6">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
