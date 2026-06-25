<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Services\AuthService;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function __construct(private readonly AuthService $authService = new AuthService())
    {
    }

    public function login()
    {
        $this->ensureBootstrapAdmin();

        $regras = [
            'email' => 'required|valid_email',
            'senha' => 'required|min_length[6]',
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $email = (string) ($dados['email'] ?? '');
        $senha = (string) ($dados['senha'] ?? '');

        $resultado = $this->authService->autenticar($email, $senha);

        if (!$resultado['success']) {
            return $this->failUnauthorized($resultado['message']);
        }

        $usuario = $resultado['usuario'];

        // Normaliza perfil para evitar problemas de capitalização/espacos
        $perfilNormalizado = strtolower(trim((string) ($usuario['perfil'] ?? '')));

        $payload = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'perfil' => $perfilNormalizado,
            'cliente_id' => $usuario['cliente_id'] ?? null,
            'exp' => time() + 86400
        ];
        
        $base64Payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64Payload, getenv('JWT_SECRET') ?: 'pimbastic_secret_key');
        $token = $base64Payload . '.' . $signature;

        unset($usuario['senha']);

        return $this->respond([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
            'data' => $usuario
        ]);
    }

    public function register()
    {
        $regras = [
            'nome' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[usuario.email]',
            'senha' => 'required|min_length[6]',
        ];

        if (!$this->validate($regras)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dados = $this->request->getJSON(true) ?? $this->request->getRawInput();

        $resultado = $this->authService->registrar([
            'nome' => $dados['nome'] ?? '',
            'email' => $dados['email'] ?? '',
            'senha' => $dados['senha'] ?? '',
            'perfil' => 'cliente',
        ]);

        if (!$resultado['success']) {
            return $this->fail($resultado['message'], 400);
        }

        return $this->respondCreated([
            'message' => $resultado['message']
        ]);
    }

    public function logout()
    {
        $this->session->destroy();
        return $this->respond(['message' => 'Logout realizado com sucesso.']);
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
