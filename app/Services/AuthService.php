<?php

namespace App\Services;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;
use Config\Database;

class AuthService
{
    private $db;

    public function __construct(
        private readonly UsuarioModel $usuarioModel = new UsuarioModel(),
        private readonly ClienteModel $clienteModel = new ClienteModel()
    ) {
        $this->db = Database::connect();
    }

    public function autenticar(string $email, string $senha): array
    {
        $usuario = $this->usuarioModel->findByEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return [
                'success' => false,
                'message' => 'Credenciais inválidas.',
            ];
        }

        return [
            'success' => true,
            'usuario' => $usuario,
        ];
    }

    public function registrar(array $dados): array
    {
        $this->db->transStart();

        $clienteId = null;

        if (($dados['perfil'] ?? 'cliente') === 'cliente') {
            $clienteData = [
                'nome' => $dados['nome'],
                'saldo_carteira' => 0,
            ];

            $this->db->table('cliente')->insert($clienteData);
            $clienteId = (int) $this->db->insertID();
        }

        $this->db->table('usuario')->insert([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha' => password_hash($dados['senha'], PASSWORD_DEFAULT),
            'perfil' => $dados['perfil'],
            'cliente_id' => $clienteId,
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false,
                'message' => 'Falha ao registrar usuário.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso.',
        ];
    }
}