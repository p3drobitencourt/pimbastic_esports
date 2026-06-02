<?php

namespace App\Database\Seeds;

use App\Models\UsuarioModel;
use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = trim((string) (env('ADMIN_EMAIL') ?: 'admin@pimbastic.local'));
        $senha = (string) (env('ADMIN_PASSWORD') ?: 'admin123');

        $usuarioModel = new UsuarioModel();

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