<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Cliente extends Entity
{
    protected $casts = [
        'id'              => 'integer',
        'saldo_carteira'  => 'float',
    ];

    /**
     * Verifica se o saldo é suficiente para o valor informado.
     */
    public function temSaldoSuficiente(float $valor): bool
    {
        return $this->attributes['saldo_carteira'] >= $valor;
    }

    /**
     * Retorna o saldo formatado em BRL.
     */
    public function getSaldoFormatado(): string
    {
        return 'R$ ' . number_format($this->attributes['saldo_carteira'], 2, ',', '.');
    }
}
