<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ResolucaoModel — Liquida resultados de jogos e atualiza status das apostas.
 *
 * Migrado de ResolucaoRepository.php (PDO cru) para Query Builder + transações ACID nativas.
 * Responsável por: listagem de jogos pendentes e processamento transacional de resultados.
 */
class ResolucaoModel extends Model
{
    protected $table      = 'jogo';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    /**
     * Retorna jogos com contagem de apostas abertas.
     * Substitui: ResolucaoRepository::getJogosPendentes()
     *
     * @return array<int, array<string, mixed>>
     */
    public function getJogosPendentes(): array
    {
        return $this->db->table('jogo j')
            ->select('j.*, c.nome AS campeonato, tc.nome AS casa, tf.nome AS fora')
            ->select('(SELECT COUNT(*) FROM aposta WHERE jogo_id = j.id AND status = "aberta") AS apostas_abertas')
            ->join('campeonato c', 'c.id = j.campeonato_id')
            ->join('time tc', 'tc.id = j.time_casa_id')
            ->join('time tf', 'tf.id = j.time_fora_id')
            ->orderBy('j.data_horario', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Processa resultado de um jogo com transação ACID.
     * Substitui: ResolucaoRepository::processarResultado() (PDO cru)
     *
     * Fluxo transacional:
     * 1. SELECT apostas vencedoras FOR UPDATE (lock pessimista)
     * 2. Creditamento atômico: saldo += (valor × odd) para cada vencedora
     * 3. UPDATE status = 'vencida' para apostas que acertaram
     * 4. UPDATE status = 'perdida' para apostas que erraram
     * 5. COMMIT ou ROLLBACK
     *
     * @param  int    $jogoId            ID do jogo finalizado
     * @param  string $resultadoVencedor 'vitoria_casa', 'empate' ou 'vitoria_fora'
     * @return array{success: bool, message: string, vencedoras?: int, perdedoras?: int}
     */
    public function processarResultado(int $jogoId, string $resultadoVencedor): array
    {
        // Valida resultado
        if (!in_array($resultadoVencedor, ['vitoria_casa', 'empate', 'vitoria_fora'], true)) {
            return ['success' => false, 'message' => 'Resultado inválido.'];
        }

        $this->db->transStart();

        // 1. Lock nas apostas vencedoras
        $apostasVencedoras = $this->db->query(
            'SELECT id, cliente_id, valor, odd_escolhida FROM aposta WHERE jogo_id = ? AND tipo_escolhido = ? AND status = "aberta" FOR UPDATE',
            [$jogoId, $resultadoVencedor]
        )->getResultArray();

        // 2. Credita prêmio para cada vencedora
        foreach ($apostasVencedoras as $aposta) {
            $premio = round((float) $aposta['valor'] * (float) $aposta['odd_escolhida'], 2);

            $this->db->table('cliente')
                ->where('id', $aposta['cliente_id'])
                ->set('saldo_carteira', 'saldo_carteira + ' . $premio, false)
                ->update();
        }

        // 3. Marca vencedoras
        $this->db->table('aposta')
            ->where('jogo_id', $jogoId)
            ->where('tipo_escolhido', $resultadoVencedor)
            ->where('status', 'aberta')
            ->update(['status' => 'vencida']);

        $qtdVencedoras = $this->db->affectedRows();

        // 4. Marca perdedoras
        $this->db->table('aposta')
            ->where('jogo_id', $jogoId)
            ->where('tipo_escolhido !=', $resultadoVencedor)
            ->where('status', 'aberta')
            ->update(['status' => 'perdida']);

        $qtdPerdedoras = $this->db->affectedRows();

        // 5. Commit
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['success' => false, 'message' => 'Falha na transação de liquidação.'];
        }

        return [
            'success'    => true,
            'message'    => "Jogo liquidado! {$qtdVencedoras} vencedora(s), {$qtdPerdedoras} perdedora(s).",
            'vencedoras' => $qtdVencedoras,
            'perdedoras' => $qtdPerdedoras,
        ];
    }
}
