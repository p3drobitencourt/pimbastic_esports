<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login', ['title' => 'Login - Pimbastic Esports']);
    }

    public function autenticar()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        if (empty($email) || empty($senha)) {
            return redirect()->back()->with('error', 'Preencha todos os campos.');
        }

        // Mock check: any email containing 'admin' gets admin, others get client sportsbook
        if (strpos(strtolower($email), 'admin') !== false) {
            $sessionData = [
                'usuario_id' => 1,
                'usuario_nome' => 'Admin Pimbastic',
                'usuario_email' => $email,
                'usuario_perfil' => 'admin',
                'logged_in' => true
            ];
            $this->session->set($sessionData);
            return redirect()->to('/admin/dashboard')->with('success', 'Bem-vindo ao Painel Administrativo!');
        } else {
            $sessionData = [
                'usuario_id' => 2,
                'usuario_nome' => 'Jogador Pimbastic',
                'usuario_email' => $email,
                'usuario_perfil' => 'cliente',
                'logged_in' => true
            ];
            $this->session->set($sessionData);
            return redirect()->to('/cliente/sportsbook')->with('success', 'Login realizado com sucesso!');
        }
    }

    public function cadastro()
    {
        return view('auth/cadastro', ['title' => 'Cadastro - Pimbastic Esports']);
    }

    public function salvar()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
            'perfil' => 'required|in_list[admin,cliente]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso! Faça seu login.');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
