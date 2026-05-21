<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="font-bebas text-4xl tracking-wider text-white"><?= isset($time) && $time ? 'Editar Time #' . esc($time['id']) : 'Cadastrar Time' ?></h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest"><?= isset($time) && $time ? 'Altere os dados do time' : 'Adicione um novo time profissional ao sistema' ?></p>
    </div>

    <div class="cyber-card p-8">
        <form action="<?= isset($time) && $time ? '/admin/times/update/' . $time['id'] : '/admin/times/store' ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="nome" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Nome do Time</label>
                <input type="text" id="nome" name="nome" value="<?= old('nome') ?? ($time['nome'] ?? '') ?>" required placeholder="ex: LOUD" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                <span class="text-[11px] text-gray-500 mt-1 block">Nome do time. Mínimo de 2 caracteres.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="tecnico" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Técnico / Coach</label>
                    <input type="text" id="tecnico" name="tecnico" value="<?= old('tecnico') ?? ($time['tecnico'] ?? '') ?>" required placeholder="ex: Stk" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                    <span class="text-[11px] text-gray-500 mt-1 block">Nome completo ou nickname do coach.</span>
                </div>
                <div>
                    <label for="sigla" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Sigla / Tag</label>
                    <input type="text" id="sigla" name="sigla" value="<?= old('sigla') ?? ($time['sigla'] ?? '') ?>" maxlength="10" placeholder="ex: LLL" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                    <span class="text-[11px] text-gray-500 mt-1 block">Sigla de abreviação (máximo 10 caracteres).</span>
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 pt-4 border-t border-white/5">
                <a href="/admin/times" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-5 py-2.5 rounded-xl text-sm font-semibold uppercase tracking-wider transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
                    <?= isset($time) && $time ? 'Atualizar Time' : 'Salvar Time' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
