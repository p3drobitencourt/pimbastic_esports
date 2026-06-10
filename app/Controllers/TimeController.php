<?php

namespace App\Controllers;

use App\Models\TimeModel;
use CodeIgniter\RESTful\ResourceController;

class TimeController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        try {
            $timeModel = new TimeModel();
            $times = $timeModel->orderBy('id', 'DESC')->findAll();
            return $this->respond(['data' => $times]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao listar times: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar os times.');
        }
    }

    public function show($id = null)
    {
        try {
            $timeModel = new TimeModel();
            $time = $timeModel->find((int) $id);

            if (!$time) {
                return $this->failNotFound('Time não encontrado.');
            }

            return $this->respond(['data' => $time]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao buscar time: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar o time.');
        }
    }

    public function store()
    {
        $regras = [
            'nome' => 'required|min_length[2]',
            'tecnico' => 'required',
            'sigla' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $dados = $this->request->getJSON(true) ?? $this->request->getPost();

            $timeModel = new TimeModel();
            $novoTime = [
                'nome' => trim((string) ($dados['nome'] ?? '')),
                'tecnico' => trim((string) ($dados['tecnico'] ?? '')),
                'sigla' => trim((string) ($dados['sigla'] ?? '')) ?: null,
            ];

            $timeModel->insert($novoTime);
            $novoTime['id'] = $timeModel->getInsertID();

            return $this->respondCreated(['data' => $novoTime]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar time: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível salvar o time.');
        }
    }

    public function update($id = null)
    {
        $regras = [
            'nome' => 'required|min_length[2]',
            'tecnico' => 'required',
            'sigla' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $timeModel = new TimeModel();

            if (!$timeModel->find((int) $id)) {
                return $this->failNotFound('Time não encontrado.');
            }

            $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();

            $timeAtualizado = [
                'nome' => trim((string) ($dados['nome'] ?? '')),
                'tecnico' => trim((string) ($dados['tecnico'] ?? '')),
                'sigla' => trim((string) ($dados['sigla'] ?? '')) ?: null,
            ];

            $timeModel->update((int) $id, $timeAtualizado);
            $timeAtualizado['id'] = (int) $id;

            return $this->respond(['data' => $timeAtualizado]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar time: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível atualizar o time.');
        }
    }

    public function delete($id = null)
    {
        try {
            $timeModel = new TimeModel();

            if (!$timeModel->find((int) $id)) {
                return $this->failNotFound('Time não encontrado.');
            }

            $timeModel->delete((int) $id);

            return $this->respondDeleted(['id' => (int) $id]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao deletar time: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível remover o time.');
        }
    }
}
