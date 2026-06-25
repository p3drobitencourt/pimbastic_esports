<?php

namespace App\Controllers;

use App\Models\ResolucaoModel;
use CodeIgniter\RESTful\ResourceController;

class LiquidacaoController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        try {
            $resolucaoModel = new ResolucaoModel();
            $jogos = $resolucaoModel->getJogosPendentes();

            return $this->respond(['data' => $jogos]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao listar jogos para liquidação: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar as liquidações.');
        }
    }

    public function processar(int $jogoId)
    {
        $regras = [
            'resultado' => 'required|in_list[vitoria_casa,empate,vitoria_fora]',
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();
            $resultado = (string) ($dados['resultado'] ?? '');

            $resolucaoModel = new ResolucaoModel();
            $resposta = $resolucaoModel->processarResultado($jogoId, $resultado);

            if (!$resposta['success']) {
                return $this->fail($resposta['message'], 400);
            }

            return $this->respond(['message' => $resposta['message']]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao processar liquidação: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Erro interno ao processar a liquidação.');
        }
    }

    /**
     * Endpoint de debug para administradores: mostra jogos pendentes e apostas relacionadas.
     */
    public function debug()
    {
        try {
            $resolucaoModel = new ResolucaoModel();
            $apostaModel = new \App\Models\ApostaModel();

            $jogos = $resolucaoModel->getJogosPendentes();

            foreach ($jogos as &$jogo) {
                $jogo['apostas'] = $apostaModel->where('jogo_id', $jogo['id'])->orderBy('criado_em', 'ASC')->findAll();
            }

            return $this->respond(['data' => $jogos]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro no debug de liquidação: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Erro ao carregar os dados de debug.');
        }
    }
}