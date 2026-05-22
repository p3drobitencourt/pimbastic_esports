<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Time extends Entity
{
    protected $casts = [
        'id' => 'integer',
    ];
}
