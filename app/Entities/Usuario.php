<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Usuario extends Entity
{
    protected $casts = [
        'id'         => 'integer',
        'cliente_id' => '?integer',
    ];

    /**
     * Verifica se o usuário é administrador.
     */
    public function isAdmin(): bool
    {
        return ($this->attributes['perfil'] ?? '') === 'admin';
    }

    /**
     * Setter: aplica hash na senha antes de persistir.
     */
    public function setSenha(string $senha): self
    {
        $this->attributes['senha'] = password_hash($senha, PASSWORD_BCRYPT);
        return $this;
    }
}
