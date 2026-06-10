<?php

namespace App\Controllers;

use App\Models\JogoModel;
use CodeIgniter\RESTful\ResourceController;

class JogoController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        try {
            $jogoModel = new JogoModel();

            $jogos = $jogoModel
                ->select('jogo.*, campeonato.nome as campeonato, tc.nome as casa, tf.nome as fora')
                ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
                ->join('time as tc', 'tc.id = jogo.time_casa_id')
                ->join('time as tf', 'tf.id = jogo.time_fora_id')
                ->orderBy('jogo.id', 'DESC')
                ->findAll();

            return $this->respond(['data' => $jogos]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao listar jogos: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar os jogos.');
        }
    }

    public function show($id = null)
    {
        try {
            $jogoModel = new JogoModel();
            
            $jogo = $jogoModel
                ->select('jogo.*, campeonato.nome as campeonato, tc.nome as casa, tf.nome as fora')
                ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
                ->join('time as tc', 'tc.id = jogo.time_casa_id')
                ->join('time as tf', 'tf.id = jogo.time_fora_id')
                ->find((int) $id);

            if (!$jogo) {
                return $this->failNotFound('Jogo não encontrado.');
            }

            return $this->respond(['data' => $jogo]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao buscar jogo: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar o jogo.');
        }
    }

    public function store()
    {
        $regras = [
            'campeonato_id' => 'required|integer',
            'time_casa_id'  => 'required|integer',
            'time_fora_id'  => 'required|integer|differs[time_casa_id]',
            'data_horario'  => 'required',
            'odd_casa'      => 'required|numeric|greater_than[1]',
            'odd_empate'    => 'required|numeric|greater_than[1]',
            'odd_fora'      => 'required|numeric|greater_than[1]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getPost();

        $dataHorario = str_replace('T', ' ', (string) ($dados['data_horario'] ?? ''));
        if (strlen($dataHorario) === 16) {
            $dataHorario .= ':00';
        }

        if (strtotime($dataHorario) <= time()) {
            return $this->failValidationErrors(['data_horario' => 'A data do jogo precisa ser futura.']);
        }

        try {
            $jogoModel = new JogoModel();
            
            $novoJogo = [
                'campeonato_id' => (int) ($dados['campeonato_id'] ?? 0),
                'time_casa_id' => (int) ($dados['time_casa_id'] ?? 0),
                'time_fora_id' => (int) ($dados['time_fora_id'] ?? 0),
                'data_horario' => $dataHorario,
                'odd_casa' => (float) ($dados['odd_casa'] ?? 0),
                'odd_empate' => (float) ($dados['odd_empate'] ?? 0),
                'odd_fora' => (float) ($dados['odd_fora'] ?? 0),
            ];

            $jogoModel->save($novoJogo);

            if ($jogoModel->errors()) {
                return $this->failValidationErrors($jogoModel->errors());
            }
            
            $novoJogo['id'] = $jogoModel->getInsertID();

            return $this->respondCreated(['data' => $novoJogo]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar jogo: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível salvar o jogo.');
        }
    }

    public function update($id = null)
    {
        $regras = [
            'campeonato_id' => 'required|integer',
            'time_casa_id'  => 'required|integer',
            'time_fora_id'  => 'required|integer|differs[time_casa_id]',
            'data_horario'  => 'required',
            'odd_casa'      => 'required|numeric|greater_than[1]',
            'odd_empate'    => 'required|numeric|greater_than[1]',
            'odd_fora'      => 'required|numeric|greater_than[1]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();

        $dataHorario = str_replace('T', ' ', (string) ($dados['data_horario'] ?? ''));
        if (strlen($dataHorario) === 16) {
            $dataHorario .= ':00';
        }

        if (strtotime($dataHorario) <= time()) {
            return $this->failValidationErrors(['data_horario' => 'A data do jogo precisa ser futura.']);
        }

        try {
            $jogoModel = new JogoModel();

            if (!$jogoModel->find((int) $id)) {
                return $this->failNotFound('Jogo não encontrado.');
            }

            $jogoAtualizado = [
                'campeonato_id' => (int) ($dados['campeonato_id'] ?? 0),
                'time_casa_id' => (int) ($dados['time_casa_id'] ?? 0),
                'time_fora_id' => (int) ($dados['time_fora_id'] ?? 0),
                'data_horario' => $dataHorario,
                'odd_casa' => (float) ($dados['odd_casa'] ?? 0),
                'odd_empate' => (float) ($dados['odd_empate'] ?? 0),
                'odd_fora' => (float) ($dados['odd_fora'] ?? 0),
            ];

            $jogoModel->update((int) $id, $jogoAtualizado);
            $jogoAtualizado['id'] = (int) $id;

            return $this->respond(['data' => $jogoAtualizado]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar jogo: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível atualizar o jogo.');
        }
    }

    public function delete($id = null)
    {
        try {
            $jogoModel = new JogoModel();

            if (!$jogoModel->find((int) $id)) {
                return $this->failNotFound('Jogo não encontrado.');
            }

            $jogoModel->delete((int) $id);

            return $this->respondDeleted(['id' => (int) $id]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao deletar jogo: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível remover o jogo.');
        }
    }
}
