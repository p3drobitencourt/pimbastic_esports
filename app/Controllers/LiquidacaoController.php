<?php

namespace App\Controllers;

use App\Models\ResolucaoModel;

class LiquidacaoController extends BaseController
{
    public function index()
    {
        $resolucaoModel = new ResolucaoModel();

        return $this->renderView('admin/liquidacao/index', [
            'title' => 'Liquidação de Apostas - Pimbastic',
            'jogos' => $resolucaoModel->getJogosPendentes(),
        ]);
    }

    public function processar(int $jogoId)
    {
        $regras = [
            'resultado' => 'required|in_list[vitoria_casa,empate,vitoria_fora]',
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = (string) $this->request->getPost('resultado');

        $resolucaoModel = new ResolucaoModel();
        $resposta = $resolucaoModel->processarResultado($jogoId, $resultado);

        if (!$resposta['success']) {
            return redirect()->back()->withInput()->with('error', $resposta['message']);
        }

        return redirect()->to('/admin/liquidacao')->with('success', $resposta['message']);
    }

    /**
     * Página de debug para administradores: mostra jogos pendentes e apostas relacionadas.
     */
    public function debug()
    {
        $resolucaoModel = new ResolucaoModel();
        $apostaModel = new \App\Models\ApostaModel();

        $jogos = $resolucaoModel->getJogosPendentes();

        foreach ($jogos as &$jogo) {
            $jogo['apostas'] = $apostaModel->where('jogo_id', $jogo['id'])->orderBy('criado_em', 'ASC')->findAll();
        }

        return $this->renderView('admin/liquidacao/debug', [
            'title' => 'Debug Liquidação - Pimbastic',
            'jogos' => $jogos,
        ]);
    }
}