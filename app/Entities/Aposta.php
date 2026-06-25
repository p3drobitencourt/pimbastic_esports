<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Entidade Aposta — Representa o estado em memória de uma aposta.
 * Mapeia mutators para formatação e cálculo de retorno potencial.
 */
class Aposta extends Entity
{
    protected $datamap = [];

    protected $casts = [
        'id'             => 'integer',
        'cliente_id'     => 'integer',
        'jogo_id'        => 'integer',
        'valor'          => 'float',
        'odd_escolhida'  => 'float',
        'criado_em'      => 'datetime',
    ];

    protected $dates = ['criado_em'];

    // ── Mutators ────────────────────────────────────────────

    /**
     * Calcula o retorno potencial (valor * odd).
     */
    public function getRetornoPotencial(): float
    {
        return round($this->attributes['valor'] * $this->attributes['odd_escolhida'], 2);
    }

    /**
     * Retorna label legível para o tipo de aposta.
     */
    public function getTipoLabel(): string
    {
        return match ($this->attributes['tipo_escolhido'] ?? '') {
            'vitoria_casa' => 'Vitória Casa',
            'empate'       => 'Empate',
            'vitoria_fora' => 'Vitória Fora',
            default        => 'Desconhecido',
        };
    }

    /**
     * Retorna classe CSS para o badge de status.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->attributes['status'] ?? '') {
            'vencida' => 'bg-emerald-950/50 text-emerald-400 border-emerald-500/30',
            'perdida' => 'bg-red-950/50 text-red-400 border-red-500/30',
            default   => 'bg-amber-950/50 text-amber-400 border-amber-500/30',
        };
    }

    /**
     * Setter: garante que o valor apostado seja positivo.
     */
    public function setValor(float $valor): self
    {
        $this->attributes['valor'] = abs($valor);
        return $this;
    }
}
