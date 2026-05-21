<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Services;

use PDO;

final class DashboardService
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return array{
     *   campeonatos: array<int, array<string, mixed>>,
     *   times: array<int, array<string, mixed>>,
     *   clientes: array<int, array<string, mixed>>,
     *   jogos: array<int, array<string, mixed>>
     * }
     */
    public function fetchViewData(): array
    {
        return [
            'campeonatos' => $this->pdo->query('SELECT id, nome, pais FROM campeonato ORDER BY id DESC LIMIT 5')->fetchAll(),
            'times' => $this->pdo->query('SELECT id, nome, sigla FROM time ORDER BY id DESC LIMIT 5')->fetchAll(),
            'clientes' => $this->pdo->query('SELECT id, nome, saldo_carteira FROM cliente ORDER BY id DESC LIMIT 5')->fetchAll(),
            'jogos' => $this->pdo->query(
                'SELECT
                    j.id,
                    c.nome AS campeonato_nome,
                    tc.nome AS time_casa_nome,
                    tf.nome AS time_fora_nome,
                    j.data_horario,
                    j.odd_casa,
                    j.odd_empate,
                    j.odd_fora
                FROM jogo j
                INNER JOIN campeonato c ON c.id = j.campeonato_id
                INNER JOIN time tc ON tc.id = j.time_casa_id
                INNER JOIN time tf ON tf.id = j.time_fora_id
                ORDER BY j.id DESC LIMIT 5'
            )->fetchAll(),
        ];
    }
}
