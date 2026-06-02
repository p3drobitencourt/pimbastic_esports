<?php

namespace App\Controllers;

use App\Models\CampeonatoModel;
use App\Models\ClienteModel;
use App\Models\JogoModel;
use App\Models\ResolucaoModel;
use App\Models\TimeModel;
use App\Models\UsuarioModel;
use Config\Database;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $db = Database::connect();
        $campeonatoModel = new CampeonatoModel();
        $timeModel = new TimeModel();
        $jogoModel = new JogoModel();
        $resolucaoModel = new ResolucaoModel();
        $usuarioModel = new UsuarioModel();
        $clienteModel = new ClienteModel();
        $jogosPendentes = $resolucaoModel->getJogosPendentes();

        $ultimosJogos = $jogoModel
            ->select('jogo.*, campeonato.nome as campeonato, tc.nome as casa, tf.nome as fora')
            ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
            ->join('time as tc', 'tc.id = jogo.time_casa_id')
            ->join('time as tf', 'tf.id = jogo.time_fora_id')
            ->orderBy('jogo.id', 'DESC')
            ->findAll(5);

        $ultimosUsuarios = $usuarioModel
            ->orderBy('id', 'DESC')
            ->findAll(5);

        $dados = [
            'title' => 'Dashboard Admin - Pimbastic',
            'server_status' => 'ONLINE',
            'metricas' => [
                'campeonatos_total' => $db->table('campeonato')->countAllResults(),
                'times_total' => $db->table('time')->countAllResults(),
                'jogos_total' => $db->table('jogo')->countAllResults(),
                'usuarios_total' => $db->table('usuario')->countAllResults(),
                'liquidacoes_pendentes' => count($jogosPendentes),
            ],
            'campeonatos' => $campeonatoModel->orderBy('id', 'DESC')->findAll(5),
            'times' => $timeModel->orderBy('id', 'DESC')->findAll(5),
            'usuarios' => $ultimosUsuarios,
            'clientes' => $clienteModel->orderBy('id', 'DESC')->findAll(5),
            'jogos' => $ultimosJogos,
            'jogos_pendentes' => $jogosPendentes,
            'ultimos_cadastros' => array_map(static function (array $jogo) {
                return [
                    'tipo' => 'Jogo',
                    'nome' => $jogo['campeonato'] . ' - ' . $jogo['casa'] . ' vs ' . $jogo['fora'],
                    'data' => $jogo['data_horario'],
                ];
            }, $ultimosJogos),
        ];

        return $this->renderView('admin/dashboard', $dados);
    }

    public function indexJogos()
    {
        $jogoModel = new JogoModel();

        $jogos = $jogoModel
            ->select('jogo.*, campeonato.nome as campeonato, tc.nome as casa, tf.nome as fora')
            ->join('campeonato', 'campeonato.id = jogo.campeonato_id')
            ->join('time as tc', 'tc.id = jogo.time_casa_id')
            ->join('time as tf', 'tf.id = jogo.time_fora_id')
            ->orderBy('jogo.id', 'DESC')
            ->paginate(10);

        return $this->renderView('admin/jogos/index', [
            'title' => 'Gerenciar Jogos - Pimbastic',
            'jogos' => $jogos,
            'pager' => $jogoModel->pager,
        ]);
    }
}
