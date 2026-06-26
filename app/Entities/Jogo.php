<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Jogo extends Entity
{
    protected $casts = [
        'id'             => 'integer',
        'campeonato_id'  => 'integer',
        'time_casa_id'   => 'integer',
        'time_fora_id'   => 'integer',
        'odd_casa'       => 'float',
        'odd_empate'     => 'float',
        'odd_fora'       => 'float',
    ];

    protected $dates = ['data_horario'];

    /**
     * Override dos getters de odds para usar a arquitetura Pari-Mutuel
     */
    public function getOddCasa(): float
    {
        $calculator = new \App\Services\OddsCalculatorService();
        return $calculator->getOdds(
            $this->id,
            (float) ($this->attributes['odd_casa'] ?? 1.01),
            (float) ($this->attributes['odd_empate'] ?? 1.01),
            (float) ($this->attributes['odd_fora'] ?? 1.01)
        )['casa'];
    }

    public function getOddEmpate(): float
    {
        $calculator = new \App\Services\OddsCalculatorService();
        return $calculator->getOdds(
            $this->id,
            (float) ($this->attributes['odd_casa'] ?? 1.01),
            (float) ($this->attributes['odd_empate'] ?? 1.01),
            (float) ($this->attributes['odd_fora'] ?? 1.01)
        )['empate'];
    }

    public function getOddFora(): float
    {
        $calculator = new \App\Services\OddsCalculatorService();
        return $calculator->getOdds(
            $this->id,
            (float) ($this->attributes['odd_casa'] ?? 1.01),
            (float) ($this->attributes['odd_empate'] ?? 1.01),
            (float) ($this->attributes['odd_fora'] ?? 1.01)
        )['fora'];
    }
}
