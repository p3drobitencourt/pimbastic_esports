<?php

namespace App\Controllers;

class CampeonatoController extends BaseController
{
    public function index()
    {
        $dados = [
            'title' => 'Gerenciar Campeonatos - Pimbastic',
            'campeonatos' => [
                ['id' => 1, 'nome' => 'CBLOL Split 2', 'pais' => 'Brasil'],
                ['id' => 2, 'nome' => 'VCT Américas', 'pais' => 'EUA'],
                ['id' => 3, 'nome' => 'CS2 Major Copenhagen', 'pais' => 'Dinamarca']
            ]
        ];
        return $this->renderView('admin/campeonatos/index', $dados);
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
        // Mock: simula busca do campeonato pelo ID
        $campeonato = ['id' => $id, 'nome' => 'CBLOL Split 2', 'pais' => 'Brasil'];

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

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/campeonatos')->with('success', "Campeonato \"$nome\" registrado com sucesso! (Mock)");
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

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/campeonatos')->with('success', "Campeonato \"$nome\" atualizado com sucesso! (Mock)");
    }
}
