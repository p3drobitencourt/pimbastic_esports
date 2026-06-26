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
    ];

    protected $validationMessages = [
        'time_fora_id' => [
            'differs' => 'O time visitante deve ser diferente do time da casa.',
        ],
    ];

    /**
     * Normaliza data_horario do formato HTML datetime-local.
     * Migrado de: JogoService::create() (substituição de 'T' e adição de ':00')
     */
    protected $beforeInsert = ['normalizarDataHorario'];
    protected $beforeUpdate = ['normalizarDataHorario'];
    protected $afterFind    = ['applyDynamicOdds'];

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

    protected function applyDynamicOdds(array $data): array
    {
        if (empty($data['data'])) {
            return $data;
        }

        $calculator = new \App\Services\OddsCalculatorService();

        if (isset($data['data']['id'])) { // Single result
            $odds = $calculator->getOdds(
                (int) $data['data']['id'],
                (float) ($data['data']['odd_casa'] ?? 1.01),
                (float) ($data['data']['odd_empate'] ?? 1.01),
                (float) ($data['data']['odd_fora'] ?? 1.01)
            );
            $data['data']['odd_casa'] = $odds['casa'];
            $data['data']['odd_empate'] = $odds['empate'];
            $data['data']['odd_fora'] = $odds['fora'];
        } elseif (is_array($data['data'])) { // Multiple results
            foreach ($data['data'] as &$row) {
                if (isset($row['id'])) {
                    $odds = $calculator->getOdds(
                        (int) $row['id'],
                        (float) ($row['odd_casa'] ?? 1.01),
                        (float) ($row['odd_empate'] ?? 1.01),
                        (float) ($row['odd_fora'] ?? 1.01)
                    );
                    $row['odd_casa'] = $odds['casa'];
                    $row['odd_empate'] = $odds['empate'];
                    $row['odd_fora'] = $odds['fora'];
                }
            }
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
