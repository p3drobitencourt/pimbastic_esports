<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function __construct(private readonly AuthService $authService = new AuthService())
    {
    }

    public function login()
    {
        $this->ensureBootstrapAdmin();

        return view('auth/login', ['title' => 'Login - Pimbastic Esports']);
    }

    public function autenticar()
    {
        $regras = [
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        $resultado = $this->authService->autenticar($email, $senha);

        if (!$resultado['success']) {
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

        $usuario = $resultado['usuario'];

        // Normaliza perfil para evitar problemas de capitalização/espacos
        $perfilNormalizado = strtolower(trim((string) ($usuario['perfil'] ?? '')));

        $this->session->regenerate(true);
        $this->session->set([
            'logado' => true,
            'logged_in' => true,
            'usuario_id' => $usuario['id'],
            'usuario_nome' => $usuario['nome'],
            'usuario_email' => $usuario['email'],
            'usuario_perfil' => $perfilNormalizado,
            'perfil' => $perfilNormalizado,
            'cliente_id' => $usuario['cliente_id'] ?? null,
        ]);

        if ($perfilNormalizado === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Bem-vindo ao painel administrativo.');
        }

        return redirect()->to('/cliente/dashboard')->with('success', 'Login realizado com sucesso.');
    }

    public function cadastro()
    {
        return view('auth/cadastro', ['title' => 'Cadastro - Pimbastic Esports']);
    }

    public function salvar()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[usuario.email]',
            'senha' => 'required|min_length[6]',
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $resultado = $this->authService->registrar([
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'senha' => $this->request->getPost('senha'),
            'perfil' => 'cliente',
        ]);

        if (!$resultado['success']) {
            return redirect()->back()->withInput()->with('error', $resultado['message']);
        }

        return redirect()->to('/login')->with('success', $resultado['message']);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    private function ensureBootstrapAdmin(): void
    {
        $usuarioModel = new UsuarioModel();

        if ($usuarioModel->where('perfil', 'admin')->first()) {
            return;
        }

        $email = strtolower(trim((string) (env('ADMIN_EMAIL') ?: 'admin@pimbastic.local')));
        $senha = (string) (env('ADMIN_PASSWORD') ?: 'admin123');

        if ($usuarioModel->findByEmail($email)) {
            return;
        }

        $usuarioModel->insert([
            'nome' => 'Administrador',
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'perfil' => 'admin',
            'cliente_id' => null,
        ]);
    }
}
