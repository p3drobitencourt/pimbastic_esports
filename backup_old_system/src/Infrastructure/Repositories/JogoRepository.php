<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class JogoRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo->query(
            'SELECT j.id, j.campeonato_id, j.time_casa_id, j.time_fora_id, j.data_horario, j.odd_casa, j.odd_empate, j.odd_fora,
                    c.nome AS campeonato_nome, tc.nome AS time_casa_nome, tf.nome AS time_fora_nome
             FROM jogo j
             INNER JOIN campeonato c ON c.id = j.campeonato_id
             INNER JOIN time tc ON tc.id = j.time_casa_id
             INNER JOIN time tf ON tf.id = j.time_fora_id
             ORDER BY j.id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT j.id, j.campeonato_id, j.time_casa_id, j.time_fora_id, j.data_horario, j.odd_casa, j.odd_empate, j.odd_fora
             FROM jogo j
             WHERE j.id = :id'
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    public function create(int $campeonatoId, int $timeCasaId, int $timeForaId, string $dataHorario, float $oddCasa, float $oddEmpate, float $oddFora): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO jogo (campeonato_id, time_casa_id, time_fora_id, data_horario, odd_casa, odd_empate, odd_fora) 
             VALUES (:campeonato_id, :time_casa_id, :time_fora_id, :data_horario, :odd_casa, :odd_empate, :odd_fora)'
        );
        return $stmt->execute([
            ':campeonato_id' => $campeonatoId,
            ':time_casa_id' => $timeCasaId,
            ':time_fora_id' => $timeForaId,
            ':data_horario' => $dataHorario,
            ':odd_casa' => $oddCasa,
            ':odd_empate' => $oddEmpate,
            ':odd_fora' => $oddFora,
        ]);
    }

    public function update(int $id, int $campeonatoId, int $timeCasaId, int $timeForaId, string $dataHorario, float $oddCasa, float $oddEmpate, float $oddFora): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE jogo 
             SET campeonato_id = :campeonato_id, time_casa_id = :time_casa_id, time_fora_id = :time_fora_id, 
                 data_horario = :data_horario, odd_casa = :odd_casa, odd_empate = :odd_empate, odd_fora = :odd_fora 
             WHERE id = :id'
        );
        return $stmt->execute([
            ':campeonato_id' => $campeonatoId,
            ':time_casa_id' => $timeCasaId,
            ':time_fora_id' => $timeForaId,
            ':data_horario' => $dataHorario,
            ':odd_casa' => $oddCasa,
            ':odd_empate' => $oddEmpate,
            ':odd_fora' => $oddFora,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM jogo WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
