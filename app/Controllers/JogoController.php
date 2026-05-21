<?php

namespace App\Controllers;

class JogoController extends BaseController
{
    /**
     * Lista todos os jogos cadastrados (mock).
     */
    public function index()
    {
        $dados = [
            'title' => 'Gerenciar Jogos - Pimbastic',
            'jogos' => [
                [
                    'id' => 1,
                    'campeonato' => 'CBLOL Split 2',
                    'casa' => 'LOUD',
                    'fora' => 'paiN Gaming',
                    'data_horario' => '2026-05-22 13:00:00',
                    'odd_casa' => 1.50,
                    'odd_empate' => 2.50,
                    'odd_fora' => 2.80
                ],
                [
                    'id' => 2,
                    'campeonato' => 'CS2 Major Copenhagen',
                    'casa' => 'Furia',
                    'fora' => 'Natus Vincere',
                    'data_horario' => '2026-05-23 15:00:00',
                    'odd_casa' => 2.10,
                    'odd_empate' => 3.10,
                    'odd_fora' => 1.90
                ],
                [
                    'id' => 3,
                    'campeonato' => 'VCT Americas Split 1',
                    'casa' => 'Sentinels',
                    'fora' => 'Leviatán',
                    'data_horario' => '2026-05-23 18:00:00',
                    'odd_casa' => 1.75,
                    'odd_empate' => 4.20,
                    'odd_fora' => 2.10
                ]
            ]
        ];

        return $this->renderView('admin/jogos/index', $dados);
    }

    /**
     * Exibe formulário de criação de novo jogo.
     */
    public function create()
    {
        $dados = [
            'title' => 'Novo Jogo - Pimbastic',
            'campeonatos' => [
                ['id' => 1, 'nome' => 'CBLOL Split 2'],
                ['id' => 2, 'nome' => 'CS2 Major Copenhagen'],
                ['id' => 3, 'nome' => 'VCT Americas Split 1']
            ],
            'times' => [
                ['id' => 1, 'nome' => 'LOUD'],
                ['id' => 2, 'nome' => 'Furia'],
                ['id' => 3, 'nome' => 'paiN Gaming'],
                ['id' => 4, 'nome' => 'Natus Vincere'],
                ['id' => 5, 'nome' => 'Sentinels'],
                ['id' => 6, 'nome' => 'Leviatán']
            ],
            'jogo' => null
        ];

        return $this->renderView('admin/jogos/form', $dados);
    }

    /**
     * Valida e processa a criação de jogo (mock).
     */
    public function store()
    {
        $regras = [
            'campeonato_id' => 'required|integer',
            'time_casa_id'  => 'required|integer',
            'time_fora_id'  => 'required|integer|differs[time_casa_id]',
            'data_horario'  => 'required',
            'odd_casa'      => 'required|numeric|greater_than[1]',
            'odd_empate'    => 'required|numeric|greater_than[1]',
            'odd_fora'      => 'required|numeric|greater_than[1]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        return redirect()->to('/admin/jogos')->with('success', 'Jogo registrado com sucesso! (Mock)');
    }

    /**
     * Exibe formulário de edição de jogo existente (mock).
     */
    public function edit($id)
    {
        $dados = [
            'title' => 'Editar Jogo #' . $id . ' - Pimbastic',
            'campeonatos' => [
                ['id' => 1, 'nome' => 'CBLOL Split 2'],
                ['id' => 2, 'nome' => 'CS2 Major Copenhagen'],
                ['id' => 3, 'nome' => 'VCT Americas Split 1']
            ],
            'times' => [
                ['id' => 1, 'nome' => 'LOUD'],
                ['id' => 2, 'nome' => 'Furia'],
                ['id' => 3, 'nome' => 'paiN Gaming'],
                ['id' => 4, 'nome' => 'Natus Vincere'],
                ['id' => 5, 'nome' => 'Sentinels'],
                ['id' => 6, 'nome' => 'Leviatán']
            ],
            'jogo' => [
                'id' => $id,
                'campeonato_id' => 1,
                'time_casa_id' => 1,
                'time_fora_id' => 3,
                'data_horario' => '2026-05-22T13:00',
                'odd_casa' => 1.50,
                'odd_empate' => 2.50,
                'odd_fora' => 2.80
            ]
        ];

        return $this->renderView('admin/jogos/form', $dados);
    }

    /**
     * Valida e processa a atualização do jogo (mock).
     */
    public function update($id)
    {
        $regras = [
            'campeonato_id' => 'required|integer',
            'time_casa_id'  => 'required|integer',
            'time_fora_id'  => 'required|integer|differs[time_casa_id]',
            'data_horario'  => 'required',
            'odd_casa'      => 'required|numeric|greater_than[1]',
            'odd_empate'    => 'required|numeric|greater_than[1]',
            'odd_fora'      => 'required|numeric|greater_than[1]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        return redirect()->to('/admin/jogos')->with('success', "Jogo #$id atualizado com sucesso! (Mock)");
    }
}
