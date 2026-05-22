<?php

namespace App\Models;

use App\Entities\Jogo;
use CodeIgniter\Model;

class JogoModel extends Model
{
    protected $table         = 'jogo';
    protected $primaryKey    = 'id';
    protected $returnType    = Jogo::class;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'campeonato_id',
        'time_casa_id',
        'time_fora_id',
        'data_horario',
        'odd_casa',
        'odd_empate',
        'odd_fora',
    ];

    protected $validationRules = [
        'campeonato_id' => 'required|integer',
        'time_casa_id'  => 'required|integer',
        'time_fora_id'  => 'required|integer|differs[time_casa_id]',
        'data_horario'  => 'required',
        'odd_casa'      => 'required|numeric|greater_than[1]',
        'odd_empate'    => 'required|numeric|greater_than[1]',
        'odd_fora'      => 'required|numeric|greater_than[1]',
    ];

    protected $validationMessages = [
        'time_fora_id' => [
            'differs' => 'O time visitante deve ser diferente do time da casa.',
        ],
        'odd_casa' => [
            'greater_than' => 'Todas as odds devem ser maiores que 1.00.',
        ],
    ];

    /**
     * Normaliza data_horario do formato HTML datetime-local.
     * Migrado de: JogoService::create() (substituição de 'T' e adição de ':00')
     */
    protected $beforeInsert = ['normalizarDataHorario'];
    protected $beforeUpdate = ['normalizarDataHorario'];

    protected function normalizarDataHorario(array $data): array
    {
        if (isset($data['data']['data_horario'])) {
            $dt = str_replace('T', ' ', $data['data']['data_horario']);
            if (strlen($dt) === 16) {
                $dt .= ':00';
            }
            $data['data']['data_horario'] = $dt;
        }

        return $data;
    }
}
