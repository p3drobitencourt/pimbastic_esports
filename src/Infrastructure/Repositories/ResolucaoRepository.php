<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;
use Exception;

final class ResolucaoRepository
{
    public function __construct(private PDO $pdo) {}

    public function getJogosPendentes(): array
    {
        // Retorna apenas jogos que já começaram/terminaram e possuem apostas abertas
        return $this->pdo->query(
            "SELECT DISTINCT j.id, c.nome AS campeonato, tc.nome AS casa, tf.nome AS fora, j.data_horario
             FROM jogo j
             JOIN campeonato c ON c.id = j.campeonato_id
             JOIN time tc ON tc.id = j.time_casa_id
             JOIN time tf ON tf.id = j.time_fora_id
             JOIN aposta a ON a.jogo_id = j.id
             WHERE j.data_horario <= NOW() AND a.status = 'aberta'
             ORDER BY j.data_horario ASC"
        )->fetchAll();
    }

    public function processarResultado(int $jogoId, string $resultadoVencedor): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Marca as apostas perdedoras
            $stmtPerdidas = $this->pdo->prepare(
                "UPDATE aposta SET status = 'perdida' 
                 WHERE jogo_id = :jog AND tipo_escolhido != :res AND status = 'aberta'"
            );
            $stmtPerdidas->execute([':jog' => $jogoId, ':res' => $resultadoVencedor]);

            // 2. Marca as apostas vencedoras
            $stmtVencidas = $this->pdo->prepare(
                "UPDATE aposta SET status = 'vencida' 
                 WHERE jogo_id = :jog AND tipo_escolhido = :res AND status = 'aberta'"
            );
            $stmtVencidas->execute([':jog' => $jogoId, ':res' => $resultadoVencedor]);

            // 3. Credita o Payout (Valor * Odd) nas carteiras dos vencedores
            $stmtPayout = $this->pdo->prepare(
                "UPDATE carteira c
                 JOIN aposta a ON c.cliente_id = a.cliente_id
                 SET c.saldo = c.saldo + (a.valor * a.odd_escolhida)
                 WHERE a.jogo_id = :jog AND a.status = 'vencida'"
            );
            $stmtPayout->execute([':jog' => $jogoId]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}