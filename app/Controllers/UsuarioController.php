<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;

class UsuarioController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        try {
            $usuarioModel = new UsuarioModel();
            $usuarios = $usuarioModel->orderBy('id', 'DESC')->findAll();
            
            // Remove as senhas antes de enviar
            foreach ($usuarios as &$u) {
                unset($u['senha']);
            }

            return $this->respond(['data' => $usuarios]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao listar usuários: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar os usuários.');
        }
    }

    public function show($id = null)
    {
        try {
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find((int) $id);

            if (!$usuario) {
                return $this->failNotFound('Usuário não encontrado.');
            }

            unset($usuario['senha']);

            return $this->respond(['data' => $usuario]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao buscar usuário: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível carregar o usuário.');
        }
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
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $usuarioModel = new UsuarioModel();
            $clienteId = null;

            $dados = $this->request->getJSON(true);

            $perfil = strtolower(trim((string) ($dados['perfil'] ?? '')));

            if ($perfil === 'cliente') {
                $clienteModel = new ClienteModel();
                $clienteModel->insert([
                    'nome' => trim((string) ($dados['nome'] ?? '')),
                    'saldo_carteira' => 0,
                ]);
                $clienteId = (int) $clienteModel->getInsertID();
            }

            $novoUsuario = [
                'nome' => trim((string) ($dados['nome'] ?? '')),
                'email' => trim((string) ($dados['email'] ?? '')),
                'senha' => password_hash((string) ($dados['senha'] ?? ''), PASSWORD_DEFAULT),
                'perfil' => $perfil,
                'cliente_id' => $clienteId,
            ];

            $usuarioModel->insert($novoUsuario);
            $novoUsuario['id'] = $usuarioModel->getInsertID();
            unset($novoUsuario['senha']);

            return $this->respondCreated(['data' => $novoUsuario]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao salvar usuário: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível salvar o usuário.');
        }
    }

    public function update($id = null)
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[usuario.email,id,' . (int) $id . ']',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find((int) $id);

            if (!$usuario) {
                return $this->failNotFound('Usuário não encontrado.');
            }

            $dadosReq = $this->request->getJSON(true);

            $clienteId = $usuario['cliente_id'] ?? null;
            $perfil = strtolower(trim((string) ($dadosReq['perfil'] ?? '')));

            if ($perfil === 'cliente' && !$clienteId) {
                $clienteModel = new ClienteModel();
                $clienteModel->insert([
                    'nome' => trim((string) ($dadosReq['nome'] ?? '')),
                    'saldo_carteira' => 0,
                ]);
                $clienteId = (int) $clienteModel->getInsertID();
            }

            $dados = [
                'nome' => trim((string) ($dadosReq['nome'] ?? '')),
                'email' => trim((string) ($dadosReq['email'] ?? '')),
                'perfil' => $perfil,
                'cliente_id' => $clienteId,
            ];

            if (!empty($dadosReq['senha'])) {
                $dados['senha'] = (string) $dadosReq['senha'];
            }

            $usuarioModel->atualizarUsuario((int) $id, $dados);

            $usuarioAtualizado = $usuarioModel->find((int) $id);
            if (isset($usuarioAtualizado['senha'])) {
                unset($usuarioAtualizado['senha']);
            }

            return $this->respond(['data' => $usuarioAtualizado]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao atualizar usuário: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível atualizar o usuário.');
        }
    }

    public function delete($id = null)
    {
        try {
            $usuarioModel = new UsuarioModel();

            if (!$usuarioModel->find((int) $id)) {
                return $this->failNotFound('Usuário não encontrado.');
            }

            $usuarioModel->delete((int) $id);

            return $this->respondDeleted(['id' => (int) $id]);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao remover usuário: {error}', ['error' => $e->getMessage()]);
            return $this->failServerError('Não foi possível remover o usuário.');
        }
    }
}

