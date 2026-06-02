<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    public function index()
    {
        $usuarioModel = new UsuarioModel();

        return $this->renderView('admin/usuarios/index', [
            'title' => 'Gerenciar Usuários - Pimbastic',
            'usuarios' => $usuarioModel->paginate(10),
            'pager' => $usuarioModel->pager,
        ]);
    }

    public function create()
    {
        $perfilPreferido = strtolower(trim((string) $this->request->getGet('perfil')));
        if (!in_array($perfilPreferido, ['admin', 'cliente'], true)) {
            $perfilPreferido = 'cliente';
        }

        return $this->renderView('admin/usuarios/form', [
            'title' => 'Novo Usuário - Pimbastic',
            'usuario' => null,
            'perfil_preferido' => $perfilPreferido,
        ]);
    }

    public function edit($id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find((int) $id);

        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
        }

        return $this->renderView('admin/usuarios/form', [
            'title' => 'Editar Usuário #' . $id . ' - Pimbastic',
            'usuario' => $usuario
        ]);
    }

    public function store()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[usuario.email]',
            'senha' => 'required|min_length[6]',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $usuarioModel = new UsuarioModel();
            $clienteId = null;

            $perfil = strtolower(trim((string) $this->request->getPost('perfil')));

            if ($perfil === 'cliente') {
                $clienteModel = new ClienteModel();
                $clienteModel->insert([
                    'nome' => trim((string) $this->request->getPost('nome')),
                    'saldo_carteira' => 0,
                ]);
                $clienteId = (int) $clienteModel->getInsertID();
            }

            $usuarioModel->insert([
                'nome' => trim((string) $this->request->getPost('nome')),
                'email' => trim((string) $this->request->getPost('email')),
                'senha' => password_hash((string) $this->request->getPost('senha'), PASSWORD_DEFAULT),
                'perfil' => $perfil,
                'cliente_id' => $clienteId,
            ]);

            return redirect()->to('/admin/usuarios')->with('success', 'Usuário registrado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar usuário: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível salvar o usuário.');
        }
    }

    public function update($id)
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[usuario.email,id,' . (int) $id . ']',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        try {
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find((int) $id);

            if (!$usuario) {
                return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
            }

            $clienteId = $usuario['cliente_id'] ?? null;
            $perfil = strtolower(trim((string) $this->request->getPost('perfil')));

            if ($perfil === 'cliente' && !$clienteId) {
                $clienteModel = new ClienteModel();
                $clienteModel->insert([
                    'nome' => trim((string) $this->request->getPost('nome')),
                    'saldo_carteira' => 0,
                ]);
                $clienteId = (int) $clienteModel->getInsertID();
            }

            $dados = [
                'nome' => trim((string) $this->request->getPost('nome')),
                'email' => trim((string) $this->request->getPost('email')),
                'perfil' => $perfil,
                'cliente_id' => $clienteId,
            ];

            if ($this->request->getPost('senha')) {
                $dados['senha'] = (string) $this->request->getPost('senha');
            }

            $usuarioModel->atualizarUsuario((int) $id, $dados);

            return redirect()->to('/admin/usuarios')->with('success', 'Usuário atualizado com sucesso.');
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar usuário: {error}', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Não foi possível atualizar o usuário.');
        }
    }

    public function delete($id)
    {
        $usuarioModel = new UsuarioModel();

        if (!$usuarioModel->find((int) $id)) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado.');
        }

        $usuarioModel->delete((int) $id);

        return redirect()->to('/admin/usuarios')->with('success', 'Usuário removido com sucesso.');
    }
}
