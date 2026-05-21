<?php

namespace App\Controllers;

class ClienteController extends BaseController
{
    public function sportsbook()
    {
        $dados = [
            'title' => 'Mercado de Apostas - Pimbastic',
            'cliente' => [
                'saldo_carteira' => 1500.50
            ],
            'jogos' => [
                [
                    'id' => 1,
                    'campeonato' => 'CBLOL Split 2',
                    'casa' => 'LOUD',
                    'fora' => 'paiN Gaming',
                    'data_horario' => 'Hoje - 13:00',
                    'odd_casa' => 1.50,
                    'odd_empate' => 2.50,
                    'odd_fora' => 2.80
                ],
                [
                    'id' => 2,
                    'campeonato' => 'CS2 Major Copenhagen',
                    'casa' => 'Furia',
                    'fora' => 'Natus Vincere',
                    'data_horario' => 'Amanhã - 15:00',
                    'odd_casa' => 2.10,
                    'odd_empate' => 3.10,
                    'odd_fora' => 1.90
                ],
                [
                    'id' => 3,
                    'campeonato' => 'VCT Americas Split 1',
                    'casa' => 'Sentinels',
                    'fora' => 'Leviatán',
                    'data_horario' => '23/05 - 18:00',
                    'odd_casa' => 1.75,
                    'odd_empate' => 4.20,
                    'odd_fora' => 2.10
                ]
            ],
            'historico' => [
                [
                    'criado_em' => '2026-05-18 10:00:00',
                    'casa' => 'LOUD',
                    'fora' => 'RED Canids',
                    'tipo_escolhido' => 'vitoria_casa',
                    'odd_escolhida' => 1.85,
                    'valor' => 50.00,
                    'status' => 'vencida'
                ],
                [
                    'criado_em' => '2026-05-19 14:30:00',
                    'casa' => 'Furia',
                    'fora' => 'Imperial',
                    'tipo_escolhido' => 'vitoria_fora',
                    'odd_escolhida' => 2.20,
                    'valor' => 100.00,
                    'status' => 'pendente'
                ]
            ]
        ];

        return $this->renderView('cliente/sportsbook', $dados);
    }

    public function apostar()
    {
        $regras = [
            'jogo_id' => 'required|integer',
            'valor'   => 'required|numeric|greater_than[0]',
            'tipo'    => 'required|in_list[casa,empate,fora]'
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $tipoAposta = $this->request->getPost('tipo');
        $valor = $this->request->getPost('valor');

        return redirect()->to('/cliente/sportsbook')->with('success', "Aposta de R$ " . number_format($valor, 2, ',', '.') . " no resultado ($tipoAposta) registrada com sucesso! (Mock)");
    }
}
