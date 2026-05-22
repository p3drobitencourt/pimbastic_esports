<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Times</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Lista de times de eSports cadastrados</p>
    </div>
    
    <?php if (($usuario_logado['perfil'] ?? '') === 'admin'): ?>
    <a href="/admin/times/create" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none flex items-center gap-1.5 shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Novo Time
    </a>
    <?php endif; ?>
</div>

<div class="cyber-card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="pb-3 px-4">ID</th>
                    <th class="pb-3 px-4">Nome do Time</th>
                    <th class="pb-3 px-4">Sigla</th>
                    <th class="pb-3 px-4">Técnico / Coach</th>
                    <th class="pb-3 px-4 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($times as $t): ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-4 font-mono text-cyan-400 font-semibold">#<?= esc($t['id']) ?></td>
                        <td class="py-4 px-4 font-bold text-white"><?= esc($t['nome']) ?></td>
                        <td class="py-4 px-4 font-mono text-gray-300">
                            <span class="px-2.5 py-0.5 bg-slate-800 rounded font-bold border border-white/5 text-cyan-400">
                                <?= esc($t['sigla'] ?: '-') ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-300"><?= esc($t['tecnico']) ?></td>
                        <td class="py-4 px-4 text-center space-x-2">
                            <?php if (($usuario_logado['perfil'] ?? '') === 'admin'): ?>
                                <a href="/admin/times/edit/<?= $t['id'] ?>" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-3 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors inline-block">
                                    Editar
                                </a>
                                <form action="/admin/times/delete/<?= $t['id'] ?>" method="POST" class="inline-block" onsubmit="return confirm('Excluir este time?')">
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
