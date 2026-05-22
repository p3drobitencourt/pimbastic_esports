<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table         = 'usuario';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['nome', 'email', 'senha', 'perfil', 'cliente_id'];

    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|max_length[255]',
        'senha' => 'permit_empty|min_length[6]',
        'perfil' => 'required|in_list[admin,cliente]',
        'cliente_id' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'O e-mail é obrigatório.',
            'valid_email' => 'Informe um e-mail válido.',
        ],
        'perfil' => [
            'in_list' => 'Perfil inválido.',
        ],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function listarComCliente(): array
    {
        return $this->select('usuario.*, cliente.nome as cliente_nome, cliente.saldo_carteira')
            ->join('cliente', 'cliente.id = usuario.cliente_id', 'left')
            ->orderBy('usuario.id', 'DESC')
            ->findAll();
    }

    public function criarUsuario(array $dados): array
    {
        if (!empty($dados['senha'])) {
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        $this->insert($dados);

        return [
            'success' => $this->db->affectedRows() > 0,
            'id' => (int) $this->getInsertID(),
        ];
    }

    public function atualizarUsuario(int $id, array $dados): array
    {
        if (array_key_exists('senha', $dados) && $dados['senha'] !== '') {
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        } else {
            unset($dados['senha']);
        }

        $this->update($id, $dados);

        return [
            'success' => $this->db->affectedRows() >= 0,
        ];
    }
}