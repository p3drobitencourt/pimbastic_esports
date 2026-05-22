<?php

namespace App\Models;

use App\Entities\Time;
use CodeIgniter\Model;

class TimeModel extends Model
{
    protected $table         = 'time';
    protected $primaryKey    = 'id';
    protected $returnType    = Time::class;
    protected $useTimestamps = false;
    protected $allowedFields = ['nome', 'tecnico', 'sigla'];

    protected $validationRules = [
        'nome'    => 'required|min_length[2]|max_length[255]',
        'tecnico' => 'required|max_length[255]',
        'sigla'   => 'permit_empty|max_length[10]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome do time é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 2 caracteres.',
        ],
        'tecnico' => [
            'required' => 'O nome do técnico é obrigatório.',
        ],
    ];
}
