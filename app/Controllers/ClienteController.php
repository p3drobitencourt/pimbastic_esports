<?php

namespace App\Controllers;

use App\Services\ApostaService;
use CodeIgniter\RESTful\ResourceController;

class ClienteController extends ResourceController
{
    protected $format = 'json';

    public function __construct(private readonly ApostaService $apostaService = new ApostaService())
    {
    }

    public function dashboard()
    {
        $clienteId = (int) (session()->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return $this->failUnauthorized('Cliente não autenticado.');
        }

        try {
            $dados = $this->apostaService->dashboard($clienteId);
            
            // Re-instancia para garantir acesso sem helper estático problemático
            $clienteModel = new \App\Models\ClienteModel();
            
            $dados['saldo_realtime'] = $clienteModel->getSaldoAtual($clienteId);
            $dados['usuario_logado'] = [
                'nome' => session()->get('usuario_nome') ?? 'Cliente',
                'perfil' => session()->get('usuario_perfil') ?? 'cliente'
            ];

            return $this->respond(['data' => $dados]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao carregar dashboard do cliente: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar os dados do dashboard.');
        }
    }

    public function adicionarSaldo()
    {
        $clienteId = (int) (session()->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return $this->failUnauthorized('Cliente não autenticado.');
        }

        $regras = ['valor' => 'required|numeric|greater_than[0]'];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();
            $valor = (float) ($dados['valor'] ?? 0);

            $clienteModel = new \App\Models\ClienteModel();
            $resultado = $clienteModel->creditarSaldo($clienteId, $valor);

            if (!$resultado['success']) {
                return $this->fail($resultado['message'], 400);
            }

            return $this->respond(['message' => $resultado['message']]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao adicionar saldo: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível processar a adição de saldo.');
        }
    }
}
