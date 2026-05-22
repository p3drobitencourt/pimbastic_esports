<?php

namespace App\Controllers;

use App\Models\TimeModel;

class TimeController extends BaseController
{
    public function index()
    {
        $timeModel = new TimeModel();

        return $this->renderView('admin/times/index', [
            'title' => 'Gerenciar Times - Pimbastic',
            'times' => $timeModel->orderBy('id', 'DESC')->paginate(10),
            'pager' => $timeModel->pager,
        ]);
    }

    public function create()
    {
        return $this->renderView('admin/times/form', [
            'title' => 'Novo Time - Pimbastic',
            'time' => null
        ]);
    }

    public function edit($id)
    {
        $timeModel = new TimeModel();
        $time = $timeModel->find((int) $id);

        if (!$time) {
            return redirect()->to('/admin/times')->with('error', 'Time não encontrado.');
        }

        return $this->renderView('admin/times/form', [
            'title' => 'Editar Time #' . $id . ' - Pimbastic',
            'time' => $time
        ]);
    }

    public function store()
    {
        $regras = [
            'nome' => 'required|min_length[2]',
            'tecnico' => 'required',
            'sigla' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $timeModel = new TimeModel();
            $timeModel->insert([
                'nome' => trim((string) $this->request->getPost('nome')),
                'tecnico' => trim((string) $this->request->getPost('tecnico')),
                'sigla' => trim((string) $this->request->getPost('sigla')) ?: null,
            ]);

            return redirect()->to('/admin/times')->with('success', 'Time registrado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar time: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível salvar o time.');
        }
    }

    public function update($id)
    {
        $regras = [
            'nome' => 'required|min_length[2]',
            'tecnico' => 'required',
            'sigla' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $timeModel = new TimeModel();

            if (!$timeModel->find((int) $id)) {
                return redirect()->to('/admin/times')->with('error', 'Time não encontrado.');
            }

            $timeModel->update((int) $id, [
                'nome' => trim((string) $this->request->getPost('nome')),
                'tecnico' => trim((string) $this->request->getPost('tecnico')),
                'sigla' => trim((string) $this->request->getPost('sigla')) ?: null,
            ]);

            return redirect()->to('/admin/times')->with('success', 'Time atualizado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar time: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível atualizar o time.');
        }
    }

    public function delete($id)
    {
        $timeModel = new TimeModel();

        if (!$timeModel->find((int) $id)) {
            return redirect()->to('/admin/times')->with('error', 'Time não encontrado.');
        }

        $timeModel->delete((int) $id);

        return redirect()->to('/admin/times')->with('success', 'Time removido com sucesso.');
    }
}
