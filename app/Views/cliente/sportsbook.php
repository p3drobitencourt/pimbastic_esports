<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<!-- Top Bar with Page Title and Balance -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="font-bebas text-4xl tracking-wider text-white">Mercado de Apostas</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Escolha os melhores confrontos de eSports</p>
    </div>
    
    <!-- Balance Card -->
    <div class="cyber-card px-6 py-4 flex flex-col items-end min-w-[200px] border-l-4 border-emerald-400">
        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Saldo Disponível</span>
        <span class="text-2xl font-bold text-emerald-400 font-mono">R$ <?= number_format($cliente['saldo_carteira'], 2, ',', '.') ?></span>
    </div>
</div>

<!-- Active Games Section -->
<h2 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 mb-6 uppercase tracking-wider">Jogos Disponíveis</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
    <?php foreach ($jogos as $jogo): ?>
        <div class="cyber-card p-6 flex flex-col justify-between" id="match-card-<?= $jogo['id'] ?>">
            <div>
                <!-- Championship Name -->
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded bg-cyan-950/65 text-cyan-300 border border-cyan-500/20 uppercase tracking-wider">
                        <?= esc($jogo['campeonato']) ?>
                    </span>
                    <span class="text-[11px] text-gray-500 flex items-center gap-1 font-mono">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?= esc($jogo['data_horario']) ?>
                    </span>
                </div>

                <!-- Match Teams display -->
                <div class="flex items-center justify-between py-6 px-2 text-center">
                    <div class="w-[42%] flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center font-bold text-lg border border-white/5 text-emerald-400 mb-2 shadow-inner">
                            <?= substr(esc($jogo['casa']), 0, 2) ?>
                        </div>
                        <span class="text-sm font-semibold text-white line-clamp-1"><?= esc($jogo['casa']) ?></span>
                    </div>

                    <div class="text-xs text-cyan-400 font-bold uppercase tracking-widest italic select-none">VS</div>

                    <div class="w-[42%] flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center font-bold text-lg border border-white/5 text-cyan-400 mb-2 shadow-inner">
                            <?= substr(esc($jogo['fora']), 0, 2) ?>
                        </div>
                        <span class="text-sm font-semibold text-white line-clamp-1"><?= esc($jogo['fora']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Interactive betting form -->
            <form method="POST" action="/cliente/apostar" class="mt-4 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="jogo_id" value="<?= $jogo['id'] ?>">
                <!-- Hidden selected option: 'casa', 'empate', 'fora' -->
                <input type="hidden" name="tipo" id="tipo-bet-<?= $jogo['id'] ?>" required>
                <!-- Hidden selected odd value to compute potential return -->
                <input type="hidden" id="odd-val-<?= $jogo['id'] ?>" value="0">

                <!-- Odd Buttons Grid -->
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="selectOdd(<?= $jogo['id'] ?>, 'casa', <?= $jogo['odd_casa'] ?>)" id="btn-<?= $jogo['id'] ?>-casa" class="flex flex-col items-center p-2 rounded-xl bg-black/40 border border-white/5 hover:border-emerald-500/50 hover:bg-black/60 transition-all text-center">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Casa</span>
                        <span class="font-bold text-sm text-emerald-400 font-mono"><?= number_format($jogo['odd_casa'], 2) ?></span>
                    </button>
                    <button type="button" onclick="selectOdd(<?= $jogo['id'] ?>, 'empate', <?= $jogo['odd_empate'] ?>)" id="btn-<?= $jogo['id'] ?>-empate" class="flex flex-col items-center p-2 rounded-xl bg-black/40 border border-white/5 hover:border-cyan-500/50 hover:bg-black/60 transition-all text-center">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Empate</span>
                        <span class="font-bold text-sm text-cyan-400 font-mono"><?= number_format($jogo['odd_empate'], 2) ?></span>
                    </button>
                    <button type="button" onclick="selectOdd(<?= $jogo['id'] ?>, 'fora', <?= $jogo['odd_fora'] ?>)" id="btn-<?= $jogo['id'] ?>-fora" class="flex flex-col items-center p-2 rounded-xl bg-black/40 border border-white/5 hover:border-emerald-500/50 hover:bg-black/60 transition-all text-center">
                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">Fora</span>
                        <span class="font-bold text-sm text-emerald-400 font-mono"><?= number_format($jogo['odd_fora'], 2) ?></span>
                    </button>
                </div>

                <!-- Input bet value -->
                <div class="flex gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-500 font-mono">R$</span>
                        <input type="number" name="valor" id="valor-bet-<?= $jogo['id'] ?>" step="0.01" min="1" oninput="calculatePayout(<?= $jogo['id'] ?>)" placeholder="0,00" class="w-full bg-black/40 border border-white/5 focus:border-cyan-400 rounded-xl pl-8 pr-3 py-2 text-white focus:outline-none transition-colors text-sm font-mono" required>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-4 py-2 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none flex items-center justify-center gap-1.5 shadow-md shadow-emerald-500/5 hover:-translate-y-0.5">
                        Apostar
                    </button>
                </div>

                <!-- Bet Calculator Hint -->
                <div id="payout-box-<?= $jogo['id'] ?>" class="hidden text-xs text-gray-400 flex justify-between items-center bg-black/25 px-3 py-1.5 rounded-lg border border-white/5 font-mono">
                    <span>Retorno estimado:</span>
                    <span class="font-bold text-emerald-400" id="payout-val-<?= $jogo['id'] ?>">R$ 0,00</span>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<!-- History Section -->
<div class="cyber-card p-6">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Histórico Recente</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-white/10 uppercase tracking-widest text-[10px]">
                    <th class="pb-3 pt-1 px-4">Data/Horário</th>
                    <th class="pb-3 pt-1 px-4">Confronto</th>
                    <th class="pb-3 pt-1 px-4">Palpite</th>
                    <th class="pb-3 pt-1 px-4 text-right">Odd</th>
                    <th class="pb-3 pt-1 px-4 text-right">Valor</th>
                    <th class="pb-3 pt-1 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $h): ?>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-4 font-mono text-gray-400 text-xs">
                            <?= date('d/m/Y H:i', strtotime($h['criado_em'])) ?>
                        </td>
                        <td class="py-4 px-4 font-semibold text-white">
                            <?= esc($h['casa']) ?> <span class="text-gray-500 font-normal">vs</span> <?= esc($h['fora']) ?>
                        </td>
                        <td class="py-4 px-4 text-gray-300">
                            <?php if ($h['tipo_escolhido'] === 'vitoria_casa'): ?>
                                Vitória Casa (<?= esc($h['casa']) ?>)
                            <?php elseif ($h['tipo_escolhido'] === 'vitoria_fora'): ?>
                                Vitória Fora (<?= esc($h['fora']) ?>)
                            <?php else: ?>
                                Empate
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-cyan-400 font-mono">
                            <?= number_format($h['odd_escolhida'], 2) ?>
                        </td>
                        <td class="py-4 px-4 text-right text-emerald-400 font-semibold font-mono">
                            R$ <?= number_format($h['valor'], 2, ',', '.') ?>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <?php if ($h['status'] === 'vencida'): ?>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-emerald-950/50 text-emerald-400 border border-emerald-500/30">Vencida</span>
                            <?php elseif ($h['status'] === 'pendente'): ?>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-amber-950/50 text-amber-400 border border-amber-500/30">Pendente</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-red-950/50 text-red-400 border border-red-500/30">Perdida</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Handles odd button click
    function selectOdd(matchId, selection, oddValue) {
        // Find inputs for the specific form
        const typeInput = document.getElementById('tipo-bet-' + matchId);
        const oddValInput = document.getElementById('odd-val-' + matchId);
        
        // Save selected value
        typeInput.value = selection;
        oddValInput.value = oddValue;

        // Toggle selected styling
        const options = ['casa', 'empate', 'fora'];
        options.forEach(opt => {
            const btn = document.getElementById(`btn-${matchId}-${opt}`);
            if (opt === selection) {
                btn.classList.add('bg-cyan-500/10', 'border-cyan-400', 'shadow-md', 'shadow-cyan-500/5');
                btn.classList.remove('bg-black/40', 'border-white/5');
            } else {
                btn.classList.remove('bg-cyan-500/10', 'border-cyan-400', 'shadow-md', 'shadow-cyan-500/5');
                btn.classList.add('bg-black/40', 'border-white/5');
            }
        });

        // Show calculator and recalculate
        const payoutBox = document.getElementById('payout-box-' + matchId);
        payoutBox.classList.remove('hidden');
        calculatePayout(matchId);
    }

    // Live payout calculator
    function calculatePayout(matchId) {
        const odd = parseFloat(document.getElementById('odd-val-' + matchId).value) || 0;
        const valInput = document.getElementById('valor-bet-' + matchId);
        const amount = parseFloat(valInput.value) || 0;
        const payoutVal = document.getElementById('payout-val-' + matchId);

        if (odd > 0 && amount > 0) {
            const result = odd * amount;
            payoutVal.textContent = 'R$ ' + result.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            payoutVal.textContent = 'R$ 0,00';
        }
    }
</script>
<?= $this->endSection() ?>
