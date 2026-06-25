<?php

namespace App\Controllers;

use App\Services\ApostaService;
use CodeIgniter\RESTful\ResourceController;

class ApostaController extends ResourceController
{
    protected $format = 'json';

    public function __construct(private readonly ApostaService $apostaService = new ApostaService())
    {
    }

    public function store()
    {
        $clienteId = (int) (session()->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return $this->failUnauthorized('Cliente não autenticado.');
        }

        $regras = [
            'jogo_id' => 'required|integer',
            'valor'   => 'required|numeric|greater_than[0]',
            'tipo'    => 'required|in_list[casa,empate,fora]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getPost();

        $resultado = $this->apostaService->registrar($clienteId, [
            'jogo_id' => $dados['jogo_id'] ?? 0,
            'valor' => $dados['valor'] ?? 0,
            'tipo' => $dados['tipo'] ?? '',
        ]);

        if (!$resultado['success']) {
            return $this->fail($resultado['message'], 400);
        }

        return $this->respondCreated(['message' => $resultado['message']]);
    }

    public function update($id = null)
    {
        $clienteId = (int) (session()->get('cliente_id') ?? 0);

        if ($clienteId <= 0) {
            return $this->failUnauthorized('Cliente não autenticado.');
        }

        $regras = [
            'jogo_id' => 'required|integer',
            'valor' => 'required|numeric|greater_than[0]',
            'tipo' => 'required|in_list[casa,empate,fora]',
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();

        $resultado = $this->apostaService->atualizar((int) $id, $clienteId, [
            'jogo_id' => $dados['jogo_id'] ?? 0,
            'valor' => $dados['valor'] ?? 0,
            'tipo' => $dados['tipo'] ?? '',
        ]);

        if (!$resultado['success']) {
            return $this->fail($resultado['message'], 400);
        }

        return $this->respond(['message' => $resultado['message']]);
    }

    public function delete($id = null)
    {
        $clienteId = (int) (session()->get('cliente_id') ?? 0);
        
        if ($clienteId <= 0) {
            return $this->failUnauthorized('Cliente não autenticado.');
        }

        $resultado = $this->apostaService->cancelar((int) $id, $clienteId);

        if (!$resultado['success']) {
            return $this->fail($resultado['message'], 400);
        }

        return $this->respondDeleted(['message' => $resultado['message'], 'id' => (int) $id]);
    }
}
