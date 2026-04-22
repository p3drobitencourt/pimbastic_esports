<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;
use Exception;

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
        $this->pdo->beginTransaction();

        try {
            // 1. Lock na linha do saldo
            $stmtLock = $this->pdo->prepare('SELECT saldo FROM carteira WHERE cliente_id = :cli FOR UPDATE');
            $stmtLock->execute([':cli' => $dados['cliente_id']]);
            $saldoAtual = (float) $stmtLock->fetchColumn();

            if ($saldoAtual < $dados['valor']) {
                throw new Exception("Saldo insuficiente.");
            }

            // 2. Deduz o saldo atômicamente
            $stmtDebito = $this->pdo->prepare('UPDATE carteira SET saldo = saldo - :val WHERE cliente_id = :cli');
            $stmtDebito->execute([
                ':val' => $dados['valor'],
                ':cli' => $dados['cliente_id']
            ]);

            // 3. Persiste a aposta
            $stmtAposta = $this->pdo->prepare(
                'INSERT INTO aposta (cliente_id, jogo_id, valor, tipo_escolhido, odd_escolhida, status) 
                 VALUES (:cli, :jog, :val, :tip, :odd, "aberta")'
            );
            $stmtAposta->execute([
                ':cli' => $dados['cliente_id'],
                ':jog' => $dados['jogo_id'],
                ':val' => $dados['valor'],
                ':tip' => $dados['tipo'],
                ':odd' => $dados['odd']
            ]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            // Em produção, propaga-se a exceção para o Controller exibir erro na View.
            return false;
        }
    }
}