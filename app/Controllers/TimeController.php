<?php

namespace App\Controllers;

class TimeController extends BaseController
{
    public function index()
    {
        $dados = [
            'title' => 'Gerenciar Times - Pimbastic',
            'times' => [
                ['id' => 1, 'nome' => 'LOUD', 'tecnico' => 'Stk', 'sigla' => 'LLL'],
                ['id' => 2, 'nome' => 'Furia', 'tecnico' => 'Fallen', 'sigla' => 'FUR'],
                ['id' => 3, 'nome' => 'paiN Gaming', 'tecnico' => 'Xis', 'sigla' => 'PNG']
            ]
        ];
        return $this->renderView('admin/times/index', $dados);
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
        $time = ['id' => $id, 'nome' => 'LOUD', 'tecnico' => 'Stk', 'sigla' => 'LLL'];

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

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/times')->with('success', "Time \"$nome\" registrado com sucesso! (Mock)");
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

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/times')->with('success', "Time \"$nome\" atualizado com sucesso! (Mock)");
    }
}
