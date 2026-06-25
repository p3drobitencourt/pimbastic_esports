<?php

namespace App\Controllers;

use App\Models\CampeonatoModel;
use CodeIgniter\RESTful\ResourceController;

class CampeonatoController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        try {
            $campeonatoModel = new CampeonatoModel();
            // Para APIs é comum não forçar paginate de HTML, mas retornar findAll() ou array paginado.
            $campeonatos = $campeonatoModel->orderBy('id', 'DESC')->findAll();
            return $this->respond(['data' => $campeonatos]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao listar campeonatos: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar os campeonatos.');
        }
    }

    public function show($id = null)
    {
        try {
            $campeonatoModel = new CampeonatoModel();
            $campeonato = $campeonatoModel->find((int) $id);

            if (!$campeonato) {
                return $this->failNotFound('Campeonato não encontrado.');
            }

            return $this->respond(['data' => $campeonato]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao buscar campeonato: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar o campeonato.');
        }
    }

    public function create()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'pais' => 'permit_empty|min_length[2]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $dados = $this->request->getJSON(true) ?? $this->request->getPost();

            $campeonatoModel = new CampeonatoModel();
            $novoCampeonato = [
                'nome' => trim((string) ($dados['nome'] ?? '')),
                'pais' => trim((string) ($dados['pais'] ?? '')) ?: null,
            ];

            $campeonatoModel->insert($novoCampeonato);
            $novoCampeonato['id'] = $campeonatoModel->getInsertID();

            return $this->respondCreated(['data' => $novoCampeonato]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar campeonato: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível salvar o campeonato.');
        }
    }

    public function update($id = null)
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'pais' => 'permit_empty|min_length[2]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $campeonatoModel = new CampeonatoModel();

            if (!$campeonatoModel->find((int) $id)) {
                return $this->failNotFound('Campeonato não encontrado.');
            }

            $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();

            $campeonatoAtualizado = [
                'nome' => trim((string) ($dados['nome'] ?? '')),
                'pais' => trim((string) ($dados['pais'] ?? '')) ?: null,
            ];

            $campeonatoModel->update((int) $id, $campeonatoAtualizado);
            $campeonatoAtualizado['id'] = (int) $id;

            return $this->respond(['data' => $campeonatoAtualizado]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar campeonato: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível atualizar o campeonato.');
        }
    }

    public function delete($id = null)
    {
        try {
            $campeonatoModel = new CampeonatoModel();

            if (!$campeonatoModel->find((int) $id)) {
                return $this->failNotFound('Campeonato não encontrado.');
            }

            $campeonatoModel->delete((int) $id);

            return $this->respondDeleted(['id' => (int) $id]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao deletar campeonato: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível remover o campeonato.');
        }
    }
}
