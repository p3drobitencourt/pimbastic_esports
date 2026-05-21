<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class ClienteApostaRepository
{
    public function __construct(private PDO $pdo) {}

    public function obterJogosDisponiveis(): array
    {
        return $this->pdo->query(
            'SELECT j.id, c.nome AS campeonato_nome, tc.nome AS time_casa, tf.nome AS time_fora, 
                    j.data_horario, j.odd_casa, j.odd_empate, j.odd_fora
             FROM jogo j
             INNER JOIN campeonato c ON c.id = j.campeonato_id
             INNER JOIN time tc ON tc.id = j.time_casa_id
             INNER JOIN time tf ON tf.id = j.time_fora_id
             WHERE j.data_horario > NOW()
             ORDER BY j.data_horario ASC'
        )->fetchAll();
    }

    public function registrarAposta(int $clienteId, int $jogoId, float $valor, string $tipo, float $odd): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO aposta (cliente_id, jogo_id, valor, tipo_escolhido, odd_escolhida, status) 
             VALUES (:cliente, :jogo, :valor, :tipo, :odd, "aberta")'
        );
        $stmt->execute([
            ':cliente' => $clienteId,
            ':jogo' => $jogoId,
            ':valor' => $valor,
            ':tipo' => $tipo,
            ':odd' => $odd
        ]);
    }
}