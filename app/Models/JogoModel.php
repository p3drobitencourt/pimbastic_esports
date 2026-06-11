<?php

namespace App\Models;

use CodeIgniter\Model;

class JogoModel extends Model
{
    protected $table         = 'jogo';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
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

    public function getComRelacionamentos(): array
    {
        return $this->select('jogo.*, campeonato.nome as camp_nome, tc.nome as casa, tf.nome as fora')
            ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
            ->join('time as tc', 'tc.id = jogo.time_casa_id')
            ->join('time as tf', 'tf.id = jogo.time_fora_id')
            ->orderBy('jogo.data_horario', 'ASC')
            ->findAll();
    }

    public function getJogosAtivos(): array
    {
        return $this->select('jogo.*, campeonato.nome as camp_nome, tc.nome as casa, tf.nome as fora')
            ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
            ->join('time as tc', 'tc.id = jogo.time_casa_id')
            ->join('time as tf', 'tf.id = jogo.time_fora_id')
            ->where('jogo.status !=', 'liquidado')
            ->where('jogo.data_horario >=', date('Y-m-d H:i:s', strtotime('+30 minutes')))
            ->orderBy('jogo.data_horario', 'ASC')
            ->findAll();
    }

    public function getFormData(): array
    {
        $campeonatoModel = new CampeonatoModel();
        $timeModel = new TimeModel();

        return [
            'campeonatos' => $campeonatoModel->getSelecionaveis(),
            'times' => $timeModel->getSelecionaveis(),
        ];
    }

    public function isDataFutura(string $dataHorario): bool
    {
        return strtotime($dataHorario) > time();
    }
}
