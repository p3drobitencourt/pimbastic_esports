<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>

<div class="flex flex-col gap-6 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="font-bebas text-4xl tracking-wider text-white">Dashboard do Cliente</h1>
            <p class="text-xs text-cyan-400 uppercase tracking-widest">Carteira, apostas, cancelamento e histórico em tempo real</p>
        </div>

        <div class="cyber-card px-6 py-4 border-l-4 border-emerald-400 min-w-[220px]">
            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Saldo em tempo real</span>
            <div class="text-3xl font-bold text-emerald-400 font-mono" id="saldo-topo">R$ 0,00</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Apostas abertas</div>
            <div class="text-3xl font-bold text-white font-mono" id="resumo-abertas">0</div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Vencidas</div>
            <div class="text-3xl font-bold text-emerald-400 font-mono" id="resumo-vencidas">0</div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Perdidas</div>
            <div class="text-3xl font-bold text-red-400 font-mono" id="resumo-perdidas">0</div>
        </div>
        <div class="cyber-card p-4">
            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Carteira</div>
            <div class="text-3xl font-bold text-cyan-400 font-mono" id="saldo-resumo">R$ 0,00</div>
        </div>
    </div>
</div>

<div class="cyber-card p-6 mb-8">
    <h3 class="text-lg font-bold text-white mb-3">Adicionar Saldo</h3>
    <p class="text-sm text-gray-400 mb-4">Você pode creditar saldo manualmente para testar o fluxo de apostas. Esse valor é simulado e não integra gateway de pagamento.</p>
    <form onsubmit="submitFormAPI(event, '/cliente/carteira/adicionar')" class="flex flex-wrap gap-3 items-center">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token-input">
        <input name="valor" type="number" step="0.01" min="1" placeholder="Valor em R$" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white w-48" required>
        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-gray-900 font-bold px-4 py-2 rounded-xl text-sm">Adicionar</button>
    </form>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 cyber-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Jogos ativos</h2>
            <span class="text-xs text-gray-400 uppercase tracking-widest"><span id="qtd-jogos">0</span> disponíveis</span>
        </div>
        <div id="jogos-container" class="grid grid-cols-1 lg:grid-cols-2 gap-4"></div>
    </div>

    <div class="cyber-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Carteira</h2>
            <span class="text-xs text-emerald-400 uppercase tracking-widest">Atualizada</span>
        </div>

        <div class="space-y-3">
            <div class="bg-black/25 rounded-xl p-4 border border-white/5">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Saldo atual</div>
                <div class="text-3xl font-bold text-emerald-400 font-mono" id="saldo-carteira">R$ 0,00</div>
            </div>
            <div class="bg-black/25 rounded-xl p-4 border border-white/5">
                <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Saldo da sessão</div>
                <div class="text-lg font-semibold text-white" id="nome-cliente">Cliente</div>
                <div class="text-xs text-gray-400" id="perfil-cliente">Perfil: cliente</div>
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
            <tbody id="apostas-container"></tbody>
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
            <tbody id="historico-container"></tbody>
        </table>
    </div>
</div>

<script>
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfTokenHash = '<?= csrf_hash() ?>';

    document.addEventListener('DOMContentLoaded', loadDashboardAPI);

    const formatMoney = (value) => Number(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    const formatDate = (dateStr) => {
        const d = new Date(dateStr);
        return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'});
    };

    // Função central que conversa com o CodeIgniter e salva o Token novo
    async function fetchAPI(url, options = {}) {
        options.headers = options.headers || {};
        options.headers['X-Requested-With'] = 'XMLHttpRequest'; // Avisa que é API
        options.headers['X-CSRF-TOKEN'] = csrfTokenHash; // Passa o token de segurança atual

        const response = await fetch(url, options);
        const data = await response.json();

        // Se o CodeIgniter devolveu um token novo de segurança, nós salvamos na memória!
        if (data.csrf) {
            csrfTokenHash = data.csrf;
        }

        return { response, data };
    }

    async function loadDashboardAPI() {
        try {
            const { response, data } = await fetchAPI('/cliente/dashboard');
            
            if (!response.ok) {
                if (response.status === 401) window.location.href = '/login';
                return;
            }

            document.getElementById('saldo-topo').innerText = formatMoney(data.saldo_realtime);
            document.getElementById('saldo-carteira').innerText = formatMoney(data.saldo_realtime);
            document.getElementById('saldo-resumo').innerText = formatMoney(data.saldo_realtime);
            document.getElementById('resumo-abertas').innerText = data.resumo?.abertas || 0;
            document.getElementById('resumo-vencidas').innerText = data.resumo?.vencidas || 0;
            document.getElementById('resumo-perdidas').innerText = data.resumo?.perdidas || 0;
            
            if(data.usuario_logado) {
                document.getElementById('nome-cliente').innerText = data.usuario_logado.nome;
                document.getElementById('perfil-cliente').innerText = 'Perfil: ' + data.usuario_logado.perfil;
            }

            document.getElementById('qtd-jogos').innerText = (data.jogos || []).length;
            renderJogos(data.jogos || []);
            renderApostas(data.apostas || []);
            renderHistorico(data.historico || []);
        } catch (error) {
            console.error("Erro na API:", error);
        }
    }

    async function submitFormAPI(event, url) {
        event.preventDefault(); 
        const formData = new FormData(event.target);
        
        // Garante que o formulário está sendo enviado com o último token válido
        formData.set(csrfTokenName, csrfTokenHash);

        try {
            const { response, data } = await fetchAPI(url, { method: 'POST', body: formData });
            
            if (data.success) {
                alert(data.message || 'Sucesso!');
                loadDashboardAPI(); 
                event.target.reset(); 
            } else {
                // Melhoria: Pega os erros específicos do CodeIgniter e mostra na tela
                let msgErro = data.message || 'Dados inválidos.';
                if (data.errors) {
                    msgErro += '\n' + Object.values(data.errors).join('\n');
                }
                alert('Erro: ' + msgErro);
            }
        } catch (err) {
            alert('Falha ao comunicar com a API.');
        }
    }

    // --- FUNÇÕES DE RENDERIZAÇÃO DOM ---
    function renderJogos(jogos) {
        const container = document.getElementById('jogos-container');
        container.innerHTML = jogos.map(jogo => `
            <div class="bg-black/25 border border-white/5 rounded-2xl p-4 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-[10px] px-2 py-1 rounded bg-cyan-950/60 text-cyan-300 border border-cyan-500/20 uppercase tracking-wider">${jogo.camp_nome}</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-sm">
                    <div class="bg-slate-900/60 rounded-xl p-2"><div class="text-gray-400 text-[10px]">CASA</div><div class="font-bold text-emerald-400">${jogo.casa}</div><div class="font-mono text-white">${Number(jogo.odd_casa).toFixed(2)}</div></div>
                    <div class="bg-slate-900/60 rounded-xl p-2"><div class="text-gray-400 text-[10px]">EMPATE</div><div class="font-bold text-cyan-400">X</div><div class="font-mono text-white">${Number(jogo.odd_empate).toFixed(2)}</div></div>
                    <div class="bg-slate-900/60 rounded-xl p-2"><div class="text-gray-400 text-[10px]">FORA</div><div class="font-bold text-emerald-400">${jogo.fora}</div><div class="font-mono text-white">${Number(jogo.odd_fora).toFixed(2)}</div></div>
                </div>
                <form onsubmit="submitFormAPI(event, '/cliente/apostar')" class="space-y-3">
                    <input type="hidden" name="jogo_id" value="${jogo.id}">
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="casa" class="peer sr-only" required>
                            <div class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase text-emerald-300 peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 text-center transition-all">Casa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="empate" class="peer sr-only">
                            <div class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase text-cyan-300 peer-checked:bg-cyan-500/20 peer-checked:border-cyan-500 text-center transition-all">Empate</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipo" value="fora" class="peer sr-only">
                            <div class="rounded-xl bg-black/40 border border-white/5 px-3 py-2 text-xs uppercase text-emerald-300 peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 text-center transition-all">Fora</div>
                        </label>
                    </div>
                    <div class="flex gap-2">
                        <input type="number" step="0.01" min="1" name="valor" placeholder="R$" class="flex-1 bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white" required>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-gray-950 font-bold uppercase text-sm hover:brightness-110">Apostar</button>
                    </div>
                </form>
            </div>
        `).join('');
    }

    function renderApostas(apostas) {
        document.getElementById('apostas-container').innerHTML = apostas.map(a => {
            const badge = a.status === 'aberta' ? `<span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-amber-950/50 text-amber-400">Aberta</span>` :
                          a.status === 'vencida' ? `<span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-emerald-950/50 text-emerald-400">Vencida</span>` :
                          `<span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-slate-800 text-slate-300">${a.status}</span>`;
            
            const acoes = a.status === 'aberta' ? `
                <div class="flex gap-2">
                    <form onsubmit="submitFormAPI(event, '/cliente/cancelar-aposta/${a.id}')">
                        <button type="submit" class="px-3 py-2 rounded-lg bg-red-500/10 text-red-300 border border-red-500/20 text-xs">Cancelar</button>
                    </form>
                </div>` : '<span class="text-xs text-gray-500">Sem ações</span>';

            return `
                <tr class="border-b border-white/5">
                    <td class="py-4 px-4 text-white font-semibold">${a.campeonato} - ${a.casa} x ${a.fora}</td>
                    <td class="py-4 px-4 text-emerald-400 font-mono">${formatMoney(a.valor)}</td>
                    <td class="py-4 px-4 text-cyan-400 font-mono">${Number(a.odd_escolhida).toFixed(2)}</td>
                    <td class="py-4 px-4">${badge}</td>
                    <td class="py-4 px-4">${acoes}</td>
                </tr>
            `;
        }).join('');
    }

    function renderHistorico(historico) {
        document.getElementById('historico-container').innerHTML = historico.map(h => `
            <tr class="border-b border-white/5">
                <td class="py-4 px-4 text-gray-400 font-mono text-xs">${formatDate(h.criado_em)}</td>
                <td class="py-4 px-4 text-white">${h.campeonato} - ${h.casa} x ${h.fora}</td>
                <td class="py-4 px-4 text-gray-300">${h.tipo_escolhido}</td>
                <td class="py-4 px-4 text-emerald-400 font-mono">${formatMoney(h.valor)}</td>
                <td class="py-4 px-4"><span class="px-2 py-1 rounded text-[10px] uppercase font-bold bg-slate-800 text-slate-200 border border-white/10">${h.status}</span></td>
            </tr>
        `).join('');
    }
</script>
<?= $this->endSection() ?>