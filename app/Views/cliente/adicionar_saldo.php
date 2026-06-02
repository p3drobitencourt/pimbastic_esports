<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="font-bebas text-4xl tracking-wider text-white">Adicionar Saldo</h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest">Insira o valor que deseja creditar na sua carteira</p>
    </div>

    <div class="cyber-card p-6">
        <div class="mb-4">
            <div class="text-xs text-gray-400 uppercase tracking-widest">Saldo atual</div>
            <div class="text-3xl font-bold text-emerald-400 font-mono">R$ <?= number_format((float) ($saldo_realtime ?? 0), 2, ',', '.') ?></div>
        </div>

        <form method="POST" action="/cliente/carteira/adicionar">
            <?= csrf_field() ?>
            <div>
                <label for="valor" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Valor (R$)</label>
                <input id="valor" name="valor" type="number" step="0.01" min="1" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-white" placeholder="Ex: 50.00">
            </div>

            <div class="flex justify-end mt-4">
                <a href="/cliente/dashboard" class="mr-3 text-xs text-gray-400">Voltar</a>
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-gray-900 font-bold px-4 py-2 rounded-xl text-sm">Adicionar Saldo</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
