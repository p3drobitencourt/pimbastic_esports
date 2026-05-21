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
        return $this->pdo->query(
            "SELECT j.id, c.nome AS campeonato, tc.nome AS casa, tf.nome AS fora, j.data_horario,
                    (SELECT COUNT(*) FROM aposta a WHERE a.jogo_id = j.id AND a.status = 'aberta') as apostas_abertas
             FROM jogo j
             JOIN campeonato c ON c.id = j.campeonato_id
             JOIN time tc ON tc.id = j.time_casa_id
             JOIN time tf ON tf.id = j.time_fora_id
             ORDER BY j.data_horario DESC"
        )->fetchAll();
    }

    public function processarResultado(int $jogoId, string $resultadoVencedor): bool
    {
        $this->pdo->beginTransaction();

        try {
            // 1. DQL com Row-level Lock para as apostas vencedoras
            $stmtVencedoras = $this->pdo->prepare(
                "SELECT id, cliente_id, valor, odd_escolhida FROM aposta 
                 WHERE jogo_id = :jog AND tipo_escolhido = :res AND status = 'aberta' FOR UPDATE"
            );
            $stmtVencedoras->execute([':jog' => $jogoId, ':res' => $resultadoVencedor]);
            $apostasVencedoras = $stmtVencedoras->fetchAll();

            // 2. Liquidação financeira individual (Resolve múltiplas entradas por cliente)
            $stmtPagar = $this->pdo->prepare(
                "UPDATE cliente SET saldo_carteira = saldo_carteira + :premio WHERE id = :cliente_id"
            );
            
            foreach ($apostasVencedoras as $aposta) {
                $premio = $aposta['valor'] * $aposta['odd_escolhida'];
                $stmtPagar->execute([
                    ':premio' => $premio,
                    ':cliente_id' => $aposta['cliente_id']
                ]);
            }

            // 3. Atualização de Status DML
            $stmtUpdateVencidas = $this->pdo->prepare(
                "UPDATE aposta SET status = 'vencida' WHERE jogo_id = :jog AND tipo_escolhido = :res AND status = 'aberta'"
            );
            $stmtUpdateVencidas->execute([':jog' => $jogoId, ':res' => $resultadoVencedor]);

            $stmtUpdatePerdidas = $this->pdo->prepare(
                "UPDATE aposta SET status = 'perdida' WHERE jogo_id = :jog AND tipo_escolhido != :res AND status = 'aberta'"
            );
            $stmtUpdatePerdidas->execute([':jog' => $jogoId, ':res' => $resultadoVencedor]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}