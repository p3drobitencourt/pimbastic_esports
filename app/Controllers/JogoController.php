<?php

namespace App\Controllers;

use App\Models\CampeonatoModel;
use App\Models\JogoModel;
use App\Models\TimeModel;

class JogoController extends BaseController
{
    public function index()
    {
        $jogoModel = new JogoModel();

        return $this->renderView('admin/jogos/index', [
            'title' => 'Gerenciar Jogos - Pimbastic',
            'jogos' => $jogoModel
                ->select('jogo.*, campeonato.nome as campeonato, tc.nome as casa, tf.nome as fora')
                ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
                ->join('time as tc', 'tc.id = jogo.time_casa_id')
                ->join('time as tf', 'tf.id = jogo.time_fora_id')
                ->orderBy('jogo.id', 'DESC')
                ->paginate(10),
            'pager' => $jogoModel->pager,
        ]);
    }

    public function create()
    {
        $jogoModel = new JogoModel();
        $formData = $jogoModel->getFormData();

        return $this->renderView('admin/jogos/form', [
            'title' => 'Novo Jogo - Pimbastic',
            'campeonatos' => $formData['campeonatos'],
            'times' => $formData['times'],
            'jogo' => null,
        ]);
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
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $dataHorario = str_replace('T', ' ', (string) $this->request->getPost('data_horario'));
        if (strlen($dataHorario) === 16) {
            $dataHorario .= ':00';
        }

        if (strtotime($dataHorario) <= time()) {
            return redirect()->back()->withInput()->with('error', 'A data do jogo precisa ser futura.');
        }

        try {
            $jogoModel = new JogoModel();
            $jogoModel->save([
                'campeonato_id' => (int) $this->request->getPost('campeonato_id'),
                'time_casa_id' => (int) $this->request->getPost('time_casa_id'),
                'time_fora_id' => (int) $this->request->getPost('time_fora_id'),
                'data_horario' => $dataHorario,
                'odd_casa' => (float) $this->request->getPost('odd_casa'),
                'odd_empate' => (float) $this->request->getPost('odd_empate'),
                'odd_fora' => (float) $this->request->getPost('odd_fora'),
            ]);

            if ($jogoModel->errors()) {
                return redirect()->back()->withInput()->with('error', $jogoModel->errors());
            }

            return redirect()->to('/admin/jogos')->with('success', 'Jogo registrado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar jogo: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível salvar o jogo.');
        }
    }

    public function edit($id)
    {
        $jogoModel = new JogoModel();
        $jogo = $jogoModel->find((int) $id);

        if (!$jogo) {
            return redirect()->to('/admin/jogos')->with('error', 'Jogo não encontrado.');
        }

        $formData = $jogoModel->getFormData();
        $jogo['data_horario'] = date('Y-m-d\TH:i', strtotime($jogo['data_horario']));

        return $this->renderView('admin/jogos/form', [
            'title' => 'Editar Jogo #' . $id . ' - Pimbastic',
            'campeonatos' => $formData['campeonatos'],
            'times' => $formData['times'],
            'jogo' => $jogo,
        ]);
    }

    public function update($id)
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
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $dataHorario = str_replace('T', ' ', (string) $this->request->getPost('data_horario'));
        if (strlen($dataHorario) === 16) {
            $dataHorario .= ':00';
        }

        if (strtotime($dataHorario) <= time()) {
            return redirect()->back()->withInput()->with('error', 'A data do jogo precisa ser futura.');
        }

        try {
            $jogoModel = new JogoModel();

            if (!$jogoModel->find((int) $id)) {
                return redirect()->to('/admin/jogos')->with('error', 'Jogo não encontrado.');
            }

            $jogoModel->update((int) $id, [
                'campeonato_id' => (int) $this->request->getPost('campeonato_id'),
                'time_casa_id' => (int) $this->request->getPost('time_casa_id'),
                'time_fora_id' => (int) $this->request->getPost('time_fora_id'),
                'data_horario' => $dataHorario,
                'odd_casa' => (float) $this->request->getPost('odd_casa'),
                'odd_empate' => (float) $this->request->getPost('odd_empate'),
                'odd_fora' => (float) $this->request->getPost('odd_fora'),
            ]);

            return redirect()->to('/admin/jogos')->with('success', 'Jogo atualizado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar jogo: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível atualizar o jogo.');
        }
    }

    public function delete($id)
    {
        $jogoModel = new JogoModel();

        if (!$jogoModel->find((int) $id)) {
            return redirect()->to('/admin/jogos')->with('error', 'Jogo não encontrado.');
        }

        $jogoModel->delete((int) $id);

        return redirect()->to('/admin/jogos')->with('success', 'Jogo removido com sucesso.');
    }
}
