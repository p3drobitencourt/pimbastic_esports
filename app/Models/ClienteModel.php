<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table         = 'cliente';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['nome', 'saldo_carteira'];

    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[255]',
        'saldo_carteira' => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'nome' => [
            'required' => 'O nome do cliente é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
        ],
    ];

    public function getSaldoAtual(int $clienteId): float
    {
        $saldo = $this->select('saldo_carteira')->find($clienteId);

        return isset($saldo['saldo_carteira']) ? (float) $saldo['saldo_carteira'] : 0.0;
    }

    public function creditarSaldo(int $clienteId, float $valor): array
    {
        $this->db->transStart();

        $this->builder()
            ->where('id', $clienteId)
            ->set('saldo_carteira', 'saldo_carteira + ' . abs($valor), false)
            ->update();

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus() !== false,
            'message' => $this->db->transStatus() !== false ? 'Saldo atualizado com sucesso.' : 'Falha ao atualizar saldo.',
        ];
    }

    public function debitarSaldo(int $clienteId, float $valor): array
    {
        $this->db->transStart();

        $updated = $this->builder()
            ->where('id', $clienteId)
            ->where('saldo_carteira >=', abs($valor))
            ->set('saldo_carteira', 'saldo_carteira - ' . abs($valor), false)
            ->update();

        $this->db->transComplete();

        if (!$updated || $this->db->transStatus() === false || $this->db->affectedRows() === 0) {
            return ['success' => false, 'message' => 'Saldo insuficiente ou falha na atualização.'];
        }

        return ['success' => true, 'message' => 'Saldo debitado com sucesso.'];
    }
}