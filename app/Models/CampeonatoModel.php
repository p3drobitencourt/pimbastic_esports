<?php

namespace App\Models;

use App\Entities\Campeonato;
use CodeIgniter\Model;

class CampeonatoModel extends Model
{
    protected $table         = 'campeonato';
    protected $primaryKey    = 'id';
    protected $returnType    = Campeonato::class;
    protected $useTimestamps = false;
    protected $allowedFields = ['nome', 'pais'];

    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[255]',
        'pais' => 'permit_empty|min_length[2]|max_length[100]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome do campeonato é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
        ],
    ];
}
