<?php
    $perfilSelecionado = old('perfil') ?? ($usuario['perfil'] ?? ($perfil_preferido ?? 'cliente'));
?>

<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="font-bebas text-4xl tracking-wider text-white"><?= isset($usuario) && $usuario ? 'Editar Usuário #' . esc($usuario['id']) : 'Cadastrar Usuário' ?></h1>
        <p class="text-xs text-cyan-400 uppercase tracking-widest"><?= isset($usuario) && $usuario ? 'Altere os dados da conta' : 'Adicione uma nova conta de acesso ao sistema' ?></p>
    </div>

    <div class="cyber-card p-8">
        <form action="<?= isset($usuario) && $usuario ? '/admin/usuarios/update/' . $usuario['id'] : '/admin/usuarios/store' ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            <?php if (isset($usuario) && $usuario): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div>
                <label for="nome" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?= old('nome') ?? ($usuario['nome'] ?? '') ?>" required placeholder="ex: João Silva" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Endereço de E-mail</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?? ($usuario['email'] ?? '') ?>" required placeholder="ex: joao@pimbastic.com" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="senha" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Senha de Acesso</label>
                    <input type="password" id="senha" name="senha" <?= isset($usuario) && $usuario ? '' : 'required' ?> placeholder="<?= isset($usuario) && $usuario ? 'Deixe vazio para manter' : 'Mínimo 6 caracteres' ?>" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:outline-none transition-colors">
                    <?php if (isset($usuario) && $usuario): ?>
                        <span class="text-[11px] text-gray-500 mt-1 block">Preencha apenas se quiser alterar a senha.</span>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="perfil" class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Perfil de Usuário</label>
                    <select id="perfil" name="perfil" class="w-full bg-black/40 border border-white/10 focus:border-cyan-400 rounded-xl px-3 py-3 text-white focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="cliente" <?= $perfilSelecionado === 'cliente' ? 'selected' : '' ?>>Cliente (Gera Carteira)</option>
                        <option value="admin" <?= $perfilSelecionado === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 pt-4 border-t border-white/5">
                <a href="/admin/usuarios" class="bg-slate-800 hover:bg-slate-700 text-gray-200 border border-white/10 px-5 py-2.5 rounded-xl text-sm font-semibold uppercase tracking-wider transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-400 hover:to-cyan-400 text-gray-950 font-bold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 uppercase tracking-wider focus:outline-none shadow-md shadow-emerald-500/10 hover:-translate-y-0.5">
                    <?= isset($usuario) && $usuario ? 'Atualizar Usuário' : 'Salvar Usuário' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
