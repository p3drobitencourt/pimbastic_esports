<?php

namespace App\Controllers;

class UsuarioController extends BaseController
{
    public function index()
    {
        $dados = [
            'title' => 'Gerenciar Usuários - Pimbastic',
            'usuarios' => [
                ['id' => 1, 'nome' => 'Admin Pimbastic', 'email' => 'admin@pimbastic.com', 'perfil' => 'admin'],
                ['id' => 2, 'nome' => 'João Apostador', 'email' => 'joao@cliente.com', 'perfil' => 'cliente'],
                ['id' => 3, 'nome' => 'Maria Silva', 'email' => 'maria@cliente.com', 'perfil' => 'cliente']
            ]
        ];
        return $this->renderView('admin/usuarios/index', $dados);
    }

    public function create()
    {
        return $this->renderView('admin/usuarios/form', [
            'title' => 'Novo Usuário - Pimbastic',
            'usuario' => null
        ]);
    }

    public function edit($id)
    {
        $usuario = ['id' => $id, 'nome' => 'João Apostador', 'email' => 'joao@cliente.com', 'perfil' => 'cliente'];

        return $this->renderView('admin/usuarios/form', [
            'title' => 'Editar Usuário #' . $id . ' - Pimbastic',
            'usuario' => $usuario
        ]);
    }

    public function store()
    {
        $regras = [
            'nome' => 'required',
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/usuarios')->with('success', "Usuário \"$nome\" registrado com sucesso! (Mock)");
    }

    public function update($id)
    {
        $regras = [
            'nome' => 'required',
            'email' => 'required|valid_email',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $nome = $this->request->getPost('nome');
        return redirect()->to('/admin/usuarios')->with('success', "Usuário \"$nome\" atualizado com sucesso! (Mock)");
    }
}
