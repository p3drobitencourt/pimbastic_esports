<?php

namespace App\Controllers;

use App\Services\ApostaService;

class ClienteController extends BaseController
{
    public function __construct(private readonly ApostaService $apostaService = new ApostaService())
    {
    }

    public function dashboard()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        }

        $dados = $this->apostaService->dashboard($clienteId);
        $dados['title'] = 'Dashboard do Cliente - Pimbastic';
        $dados['saldo_realtime'] = $this->getClienteSaldo();

        return $this->renderView('cliente/dashboard', $dados);
    }

    public function sportsbook()
    {
        return $this->dashboard();
    }

    public function apostar()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        }

        $regras = [
            'jogo_id' => 'required|integer',
            'valor'   => 'required|numeric|greater_than[0]',
            'tipo'    => 'required|in_list[casa,empate,fora]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = $this->apostaService->registrar($clienteId, [
            'jogo_id' => $this->request->getPost('jogo_id'),
            'valor' => $this->request->getPost('valor'),
            'tipo' => $this->request->getPost('tipo'),
        ]);

        if (!$resultado['success']) {
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

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
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = $this->apostaService->atualizar($id, $clienteId, [
            'jogo_id' => $this->request->getPost('jogo_id'),
            'valor' => $this->request->getPost('valor'),
            'tipo' => $this->request->getPost('tipo'),
        ]);

        if (!$resultado['success']) {
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }

    public function cancelarAposta(int $id)
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);
        $resultado = $this->apostaService->cancelar($id, $clienteId);

        if (!$resultado['success']) {
            return redirect()->back()->with('error', $resultado['message']);
        }

        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }

    public function carteira()
    {
        return $this->dashboard();
    }

    public function showAdicionarSaldo()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        }

        $dados = [
            'title' => 'Adicionar Saldo - Pimbastic',
            'saldo_realtime' => $this->getClienteSaldo(),
        ];

        return $this->renderView('cliente/adicionar_saldo', $dados);
    }

    public function adicionarSaldo()
    {
        $clienteId = (int) ($this->session->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return redirect()->to('/login')->with('error', 'Cliente não autenticado.');
        }

        $regras = [
            'valor' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $valor = (float) $this->request->getPost('valor');

        $clienteModel = new \App\Models\ClienteModel();
        $resultado = $clienteModel->creditarSaldo($clienteId, $valor);

        if (!$resultado['success']) {
            return redirect()->back()->with('error', $resultado['message']);
        }

        return redirect()->to('/cliente/dashboard')->with('success', $resultado['message']);
    }
}
