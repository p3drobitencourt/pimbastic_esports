<?php

namespace App\Controllers;

use App\Models\CampeonatoModel;

class CampeonatoController extends BaseController
{
    public function index()
    {
        $campeonatoModel = new CampeonatoModel();

        return $this->renderView('admin/campeonatos/index', [
            'title' => 'Gerenciar Campeonatos - Pimbastic',
            'campeonatos' => $campeonatoModel->orderBy('id', 'DESC')->paginate(10),
            'pager' => $campeonatoModel->pager,
        ]);
    }

    public function create()
    {
        return $this->renderView('admin/campeonatos/form', [
            'title' => 'Novo Campeonato - Pimbastic',
            'campeonato' => null
        ]);
    }

    public function edit($id)
    {
        $campeonatoModel = new CampeonatoModel();
        $campeonato = $campeonatoModel->find((int) $id);

        if (!$campeonato) {
            return redirect()->to('/admin/campeonatos')->with('error', 'Campeonato não encontrado.');
        }

        return $this->renderView('admin/campeonatos/form', [
            'title' => 'Editar Campeonato #' . $id . ' - Pimbastic',
            'campeonato' => $campeonato
        ]);
    }

    public function store()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'pais' => 'permit_empty|min_length[2]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $campeonatoModel = new CampeonatoModel();
            $campeonatoModel->insert([
                'nome' => trim((string) $this->request->getPost('nome')),
                'pais' => trim((string) $this->request->getPost('pais')) ?: null,
            ]);

            return redirect()->to('/admin/campeonatos')->with('success', 'Campeonato registrado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar campeonato: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível salvar o campeonato.');
        }
    }

    public function update($id)
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'pais' => 'permit_empty|min_length[2]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $campeonatoModel = new CampeonatoModel();

            if (!$campeonatoModel->find((int) $id)) {
                return redirect()->to('/admin/campeonatos')->with('error', 'Campeonato não encontrado.');
            }

            $campeonatoModel->update((int) $id, [
                'nome' => trim((string) $this->request->getPost('nome')),
                'pais' => trim((string) $this->request->getPost('pais')) ?: null,
            ]);

            return redirect()->to('/admin/campeonatos')->with('success', 'Campeonato atualizado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar campeonato: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível atualizar o campeonato.');
        }
    }

    public function delete($id)
    {
        $campeonatoModel = new CampeonatoModel();

        if (!$campeonatoModel->find((int) $id)) {
            return redirect()->to('/admin/campeonatos')->with('error', 'Campeonato não encontrado.');
        }

        $campeonatoModel->delete((int) $id);

        return redirect()->to('/admin/campeonatos')->with('success', 'Campeonato removido com sucesso.');
    }
}
