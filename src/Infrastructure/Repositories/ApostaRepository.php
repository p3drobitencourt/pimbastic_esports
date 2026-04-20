<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class ApostaRepository
{
    public function __construct(private PDO $pdo) {}

    public function getMercadoAtivo(): array
    {
        return $this->pdo->query(
            'SELECT j.id, c.nome AS campeonato, tc.nome AS casa, tf.nome AS fora, 
                    j.data_horario, j.odd_casa, j.odd_empate, j.odd_fora
             FROM jogo j
             JOIN campeonato c ON c.id = j.campeonato_id
             JOIN time tc ON tc.id = j.time_casa_id
             JOIN time tf ON tf.id = j.time_fora_id
             WHERE j.data_horario > NOW()
             ORDER BY j.data_horario ASC'
        )->fetchAll();
    }

    public function salvarAposta(array $dados): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO aposta (cliente_id, jogo_id, valor, tipo_escolhido, odd_escolhida, status) 
             VALUES (:cli, :jog, :val, :tip, :odd, "aberta")'
        );
        return $stmt->execute([
            ':cli' => $dados['cliente_id'],
            ':jog' => $dados['jogo_id'],
            ':val' => $dados['valor'],
            ':tip' => $dados['tipo'],
            ':odd' => $dados['odd']
        ]);
    }
}