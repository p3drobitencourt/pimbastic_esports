<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="font-bebas text-4xl tracking-wider text-white"><?= isset($campeonato) && $campeonato ? 'Editar Campeonato #' . esc($campeonato['id']) : 'Cadastrar Campeonato' ?></h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest"><?= isset($campeonato) && $campeonato ? 'Altere os dados do torneio' : 'Adicione um novo torneio ao mercado de apostas' ?></p>
    </div>

    <div class="cyber-card p-8">
        <form action="<?= isset($campeonato) && $campeonato ? '/admin/campeonatos/update/' . $campeonato['id'] : '/admin/campeonatos/store' ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <?php if (isset($campeonato) && $campeonato): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div>
                <label for="nome" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Nome do Campeonato</label>
                <input type="text" id="nome" name="nome" value="<?= old('nome') ?? ($campeonato['nome'] ?? '') ?>" required placeholder="ex: CBLOL Split 2" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                <span class="text-[11px] text-gray-500 mt-1 block">Insira o nome principal do campeonato. Mínimo de 3 caracteres.</span>
            </div>

            <div>
                <label for="pais" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Região ou País (Opcional)</label>
                <input type="text" id="pais" name="pais" value="<?= old('pais') ?? ($campeonato['pais'] ?? '') ?>" placeholder="ex: Brasil" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                <span class="text-[11px] text-gray-500 mt-1 block">Insira a nacionalidade do torneio, ou deixe vazio para Global/Internacional.</span>
            </div>

            <div class="flex justify-end items-center gap-4 pt-4 border-t border-white/5">
                <a href="/admin/campeonatos" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-5 py-2.5 rounded-xl text-sm font-semibold uppercase tracking-wider transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
                    <?= isset($campeonato) && $campeonato ? 'Atualizar Campeonato' : 'Salvar Campeonato' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
