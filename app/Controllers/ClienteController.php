<?php

namespace App\Controllers;

use App\Services\ApostaService;

class ClienteController extends BaseController
{
    public function __construct(private readonly ApostaService $apostaService = new ApostaService())
    {
    }

    // --- FUNÇÃO AJUDANTE PARA A API: Sempre devolve JSON com o Token de Segurança (CSRF) atualizado ---
    private function sendJSON(int $status, array $data)
    {
        $data['csrf'] = csrf_hash(); // Manda o token novo para o JS
        return $this->response->setStatusCode($status)->setJSON($data);
    }

    public function dashboard()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            if ($this->request->isAJAX()) return $this->sendJSON(401, ['success' => false, 'message' => 'Cliente não autenticado.']);
            return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        }

        // Se for requisição da API (JS)
        if ($this->request->isAJAX()) {
            $dados = $this->apostaService->dashboard($clienteId);
            $dados['saldo_realtime'] = $this->getClienteSaldo();
            $dados['usuario_logado'] = [
                'nome' => $this->session->get('usuario')['nome'] ?? 'Cliente',
                'perfil' => $this->session->get('usuario')['perfil'] ?? 'cliente'
            ];
            return $this->sendJSON(200, $dados);
        }

        return $this->renderView('cliente/dashboard', ['title' => 'Dashboard do Cliente - Pimbastic']);
    }

    public function sportsbook() { return $this->dashboard(); }
    public function carteira() { return $this->dashboard(); }

    public function apostar()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) return $this->sendJSON(401, ['success' => false, 'message' => 'Cliente não autenticado.']);

        $regras = [
            'jogo_id' => 'required|integer',
            'valor'   => 'required|numeric|greater_than[0]',
            'tipo'    => 'required|in_list[casa,empate,fora]'
        ];

        if (!$this->validate($regras)) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => 'Dados inválidos.', 'errors' => $this->validator->getErrors()]);
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = $this->apostaService->registrar($clienteId, [
            'jogo_id' => $this->request->getPost('jogo_id'),
            'valor' => $this->request->getPost('valor'),
            'tipo' => $this->request->getPost('tipo'),
        ]);

        if (!$resultado['success']) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => $resultado['message']]);
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

        if ($this->request->isAJAX()) return $this->sendJSON(200, ['success' => true, 'message' => $resultado['message']]);
        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }

    public function atualizarAposta(int $id)
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        $regras = [
            'jogo_id' => 'required|integer',
            'valor' => 'required|numeric|greater_than[0]',
            'tipo' => 'required|in_list[casa,empate,fora]',
        ];

        if (!$this->validate($regras)) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => 'Dados inválidos.']);
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = $this->apostaService->atualizar($id, $clienteId, [
            'jogo_id' => $this->request->getPost('jogo_id'),
            'valor' => $this->request->getPost('valor'),
            'tipo' => $this->request->getPost('tipo'),
        ]);

        if (!$resultado['success']) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => $resultado['message']]);
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

        if ($this->request->isAJAX()) return $this->sendJSON(200, ['success' => true, 'message' => $resultado['message']]);
        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }

    public function cancelarAposta(int $id)
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);
        $resultado = $this->apostaService->cancelar($id, $clienteId);

        if (!$resultado['success']) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => $resultado['message']]);
            return redirect()->back()->with('error', $resultado['message']);
        }

        if ($this->request->isAJAX()) return $this->sendJSON(200, ['success' => true, 'message' => $resultado['message']]);
        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }

    public function showAdicionarSaldo()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);
        if ($clienteId <= 0) return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        return $this->renderView('cliente/adicionar_saldo', ['title' => 'Adicionar Saldo', 'saldo_realtime' => $this->getClienteSaldo()]);
    }

    public function adicionarSaldo()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) return $this->sendJSON(401, ['success' => false, 'message' => 'Não autenticado']);

        if (!$this->validate(['valor' => 'required|numeric|greater_than[0]'])) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => 'Valor inválido']);
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $valor = (float) $this->request->getPost('valor');
        $clienteModel = new \App\Models\ClienteModel();
        $resultado = $clienteModel->creditarSaldo($clienteId, $valor);

        if (!$resultado['success']) {
            if ($this->request->isAJAX()) return $this->sendJSON(400, ['success' => false, 'message' => $resultado['message']]);
            return redirect()->back()->with('error', $resultado['message']);
        }

        if ($this->request->isAJAX()) return $this->sendJSON(200, ['success' => true, 'message' => $resultado['message']]);
        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }
}