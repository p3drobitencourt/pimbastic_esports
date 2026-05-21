<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $dados = [
            'title' => 'Dashboard Admin - Pimbastic',
            'server_status' => 'ONLINE',
            'metricas' => [
                'usuarios_ativos' => 124,
                'apostas_pendentes' => 45,
                'volume_financeiro' => 12500.00
            ],
            'campeonatos' => [
                ['id' => 1, 'nome' => 'CBLOL Split 2', 'pais' => 'Brasil'],
                ['id' => 2, 'nome' => 'VCT Américas', 'pais' => 'EUA']
            ],
            'times' => [
                ['id' => 1, 'nome' => 'LOUD', 'sigla' => 'LLL'],
                ['id' => 2, 'nome' => 'Furia', 'sigla' => 'FUR']
            ],
            'ultimos_cadastros' => [
                ['tipo' => 'Campeonato', 'nome' => 'Major 2026', 'data' => 'Hoje'],
                ['tipo' => 'Time', 'nome' => 'Sentinels', 'data' => 'Ontem'],
                ['tipo' => 'Usuário', 'nome' => 'João Apostador', 'data' => 'Ontem'],
            ]
        ];

        return $this->renderView('admin/dashboard', $dados);
    }
}
