<?php

namespace App\Models;

use App\Entities\Aposta;
use CodeIgniter\Model;

/**
 * ApostaModel — ORM nativo CI4 para a tabela `aposta`.
 *
 * Migrado de ApostaRepository.php (PDO cru) para Query Builder + transações ACID nativas.
 * Responsável por: CRUD de apostas, dedução de saldo, e mercado ativo.
 */
class ApostaModel extends Model
{
    protected $table         = 'aposta';
    protected $primaryKey    = 'id';
    protected $returnType    = Aposta::class;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'cliente_id',
        'jogo_id',
        'valor',
        'tipo_escolhido',
        'odd_escolhida',
        'status',
        'criado_em',
    ];

    protected $validationRules = [
        'cliente_id'     => 'required|integer',
        'jogo_id'        => 'required|integer',
        'valor'          => 'required|numeric|greater_than[0]',
        'tipo_escolhido' => 'required|in_list[vitoria_casa,empate,vitoria_fora]',
        'odd_escolhida'  => 'required|numeric|greater_than[1]',
    ];

    protected $validationMessages = [
        'valor' => [
            'greater_than' => 'O valor da aposta deve ser maior que zero.',
        ],
        'tipo_escolhido' => [
            'in_list' => 'Tipo de aposta inválido. Escolha: vitoria_casa, empate ou vitoria_fora.',
        ],
    ];

    // ── Queries de domínio ──────────────────────────────────

    /**
     * Retorna jogos futuros com dados dos campeonatos e times (mercado ativo).
     * Substitui: ApostaRepository::getMercadoAtivo() / ClienteApostaRepository::obterJogosDisponiveis()
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMercadoAtivo(): array
    {
        return $this->db->table('jogo j')
            ->select('j.*, c.nome AS campeonato, tc.nome AS casa, tf.nome AS fora')
            ->join('campeonato c', 'c.id = j.campeonato_id')
            ->join('time tc', 'tc.id = j.time_casa_id')
            ->join('time tf', 'tf.id = j.time_fora_id')
            ->where('j.data_horario >', date('Y-m-d H:i:s'))
            ->orderBy('j.data_horario', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Retorna histórico de apostas do cliente com dados dos jogos e times.
     * Substitui: ApostaRepository::getHistoricoApostas()
     *
     * @return array<int, array<string, mixed>>
     */
    public function getHistoricoPorCliente(int $clienteId): array
    {
        return $this->db->table('aposta a')
            ->select('a.*, tc.nome AS casa, tf.nome AS fora')
            ->join('jogo j', 'j.id = a.jogo_id')
            ->join('time tc', 'tc.id = j.time_casa_id')
            ->join('time tf', 'tf.id = j.time_fora_id')
            ->where('a.cliente_id', $clienteId)
            ->orderBy('a.criado_em', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ── Transações ACID ─────────────────────────────────────

    /**
     * Registra uma aposta com dedução atômica de saldo.
     * Substitui: ApostaRepository::salvarAposta() (PDO cru com BEGIN/COMMIT/ROLLBACK)
     *
     * Fluxo transacional:
     * 1. SELECT saldo FOR UPDATE (lock pessimista)
     * 2. Valida saldo >= valor
     * 3. UPDATE saldo_carteira -= valor
     * 4. INSERT aposta (status = 'aberta')
     * 5. COMMIT ou ROLLBACK
     *
     * @param  array{cliente_id: int, jogo_id: int, valor: float, tipo_escolhido: string, odd_escolhida: float} $dados
     * @return array{success: bool, message: string, aposta_id?: int}
     */
    public function registrarApostaComSaldo(array $dados): array
    {
        $this->db->transStart();

        // 1. Lock pessimista no saldo do cliente (evita race condition)
        $cliente = $this->db->table('cliente')
            ->select('saldo_carteira')
            ->where('id', $dados['cliente_id'])
            ->getCompiledSelect();

        // Executa com FOR UPDATE para lock de linha
        $result = $this->db->query($cliente . ' FOR UPDATE');
        $row    = $result->getRowArray();

        if (!$row) {
            $this->db->transRollback();
            return ['success' => false, 'message' => 'Cliente não encontrado.'];
        }

        $saldoAtual = (float) $row['saldo_carteira'];

        // 2. Valida saldo suficiente
        if ($saldoAtual < $dados['valor']) {
            $this->db->transRollback();
            return ['success' => false, 'message' => 'Saldo insuficiente. Saldo atual: R$ ' . number_format($saldoAtual, 2, ',', '.')];
        }

        // 3. Deduz saldo atomicamente
        $this->db->table('cliente')
            ->where('id', $dados['cliente_id'])
            ->set('saldo_carteira', 'saldo_carteira - ' . (float) $dados['valor'], false)
            ->update();

        // 4. Insere a aposta com status 'aberta'
        $this->insert([
            'cliente_id'     => $dados['cliente_id'],
            'jogo_id'        => $dados['jogo_id'],
            'valor'          => $dados['valor'],
            'tipo_escolhido' => $dados['tipo_escolhido'],
            'odd_escolhida'  => $dados['odd_escolhida'],
            'status'         => 'aberta',
            'criado_em'      => date('Y-m-d H:i:s'),
        ]);

        $apostaId = $this->getInsertID();

        // 5. Commit ou rollback automático
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['success' => false, 'message' => 'Falha na transação. Tente novamente.'];
        }

        return ['success' => true, 'message' => 'Aposta registrada com sucesso!', 'aposta_id' => $apostaId];
    }

    /**
     * Deposita valor na carteira do cliente com transação ACID.
     * Substitui: ApostaRepository::depositar()
     *
     * @return array{success: bool, message: string}
     */
    public function depositarSaldo(int $clienteId, float $valor): array
    {
        $this->db->transStart();

        $this->db->table('cliente')
            ->where('id', $clienteId)
            ->set('saldo_carteira', 'saldo_carteira + ' . abs($valor), false)
            ->update();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['success' => false, 'message' => 'Falha ao processar depósito.'];
        }

        return ['success' => true, 'message' => 'Depósito realizado com sucesso!'];
    }
}
