<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="font-bebas text-4xl tracking-wider text-white"><?= isset($jogo) && $jogo ? 'Editar Jogo #' . esc($jogo['id']) : 'Cadastrar Jogo' ?></h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest"><?= isset($jogo) && $jogo ? 'Altere os dados do confronto' : 'Adicione um novo confronto ao mercado de apostas' ?></p>
    </div>

    <div class="cyber-card p-8">
        <form action="<?= isset($jogo) && $jogo ? '/admin/jogos/update/' . $jogo['id'] : '/admin/jogos/store' ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <?php if (isset($jogo) && $jogo): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <!-- Campeonato -->
            <div>
                <label for="campeonato_id" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Campeonato</label>
                <select id="campeonato_id" name="campeonato_id" required class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white focus:outline-none transition-colors appearance-none cursor-pointer">
                    <option value="">Selecione o campeonato...</option>
                    <?php foreach ($campeonatos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (old('campeonato_id') ?? ($jogo['campeonato_id'] ?? '')) == $c['id'] ? 'selected' : '' ?>><?= esc($c['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Times -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="time_casa_id" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Time da Casa</label>
                    <select id="time_casa_id" name="time_casa_id" required class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="">Selecione...</option>
                        <?php foreach ($times as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= (old('time_casa_id') ?? ($jogo['time_casa_id'] ?? '')) == $t['id'] ? 'selected' : '' ?>><?= esc($t['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="time_fora_id" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Time Visitante</label>
                    <select id="time_fora_id" name="time_fora_id" required class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="">Selecione...</option>
                        <?php foreach ($times as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= (old('time_fora_id') ?? ($jogo['time_fora_id'] ?? '')) == $t['id'] ? 'selected' : '' ?>><?= esc($t['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-[11px] text-gray-500 mt-1 block">Deve ser diferente do time da casa.</span>
                </div>
            </div>

            <!-- Data/Hora -->
            <div>
                <label for="data_horario" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Data e Horário</label>
                <input type="datetime-local" id="data_horario" name="data_horario" value="<?= old('data_horario') ?? ($jogo['data_horario'] ?? '') ?>" required class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white focus:outline-none transition-colors">
            </div>

            <!-- Odds Iniciais (Fallback Pricing / Morning Lines) -->
            <div>
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Cotações de Abertura (Morning Lines)</div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="odd_casa" class="block text-[10px] text-emerald-400 uppercase tracking-widest mb-1 text-center font-bold">Odd Inicial (Casa)</label>
                        <input type="number" id="odd_casa" name="odd_casa" step="0.01" min="1.01" value="<?= old('odd_casa') ?? ($jogo['odd_casa'] ?? '') ?>" required placeholder="1.50" class="w-full bg-black/40 border border-white/10 focus:border-emerald-400 rounded-xl px-3 py-2.5 text-white text-center font-mono font-bold focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="odd_empate" class="block text-[10px] text-cyan-400 uppercase tracking-widest mb-1 text-center font-bold">Odd Inicial (Empate)</label>
                        <input type="number" id="odd_empate" name="odd_empate" step="0.01" min="1.01" value="<?= old('odd_empate') ?? ($jogo['odd_empate'] ?? '') ?>" required placeholder="2.50" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-3 py-2.5 text-white text-center font-mono font-bold focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label for="odd_fora" class="block text-[10px] text-emerald-400 uppercase tracking-widest mb-1 text-center font-bold">Odd Inicial (Fora)</label>
                        <input type="number" id="odd_fora" name="odd_fora" step="0.01" min="1.01" value="<?= old('odd_fora') ?? ($jogo['odd_fora'] ?? '') ?>" required placeholder="2.80" class="w-full bg-black/40 border border-white/10 focus:border-emerald-400 rounded-xl px-3 py-2.5 text-white text-center font-mono font-bold focus:outline-none transition-colors">
                    </div>
                </div>
                <span class="text-[11px] text-cyan-400/80 mt-2 block text-center">Nota: Estas são apenas as odds iniciais/fallback. Com a entrada de apostas, o sistema Pari-Mutuel calculará as cotações automaticamente.</span>
            </div>

            <div class="flex justify-end items-center gap-4 pt-4 border-t border-white/5">
                <a href="/admin/jogos" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-5 py-2.5 rounded-xl text-sm font-semibold uppercase tracking-wider transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
                    <?= isset($jogo) && $jogo ? 'Atualizar Jogo' : 'Salvar Jogo' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
