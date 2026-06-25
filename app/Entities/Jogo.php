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
}
