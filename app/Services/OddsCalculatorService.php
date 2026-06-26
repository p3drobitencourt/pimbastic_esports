<?php

namespace App\Services;

use Config\Database;
use Config\Services;

/**
 * Serviço responsável por calcular odds usando a arquitetura Pari-Mutuel (Pool Betting).
 */
class OddsCalculatorService
{
    private const VIGORISH = 0.05; // 5% de taxa da casa

    /**
     * Retorna as odds calculadas para os 3 mercados possíveis de um jogo.
     * Utiliza cache invalidado quando novas apostas são feitas.
     *
     * @param int $jogoId
     * @param float $baseCasa
     * @param float $baseEmpate
     * @param float $baseFora
     * @return array{casa: float, empate: float, fora: float}
     */
    public function getOdds(int $jogoId, float $baseCasa = 1.01, float $baseEmpate = 1.01, float $baseFora = 1.01): array
    {
        $cache = Services::cache();
        $cacheKey = 'odds_jogo_' . $jogoId;

        if ($cachedOdds = $cache->get($cacheKey)) {
            return $cachedOdds;
        }

        $odds = $this->calculateOddsForGame($jogoId, $baseCasa, $baseEmpate, $baseFora);
        
        // Cacheia indefinidamente até que uma aposta invalide (ou TTL seguro de 1 dia)
        $cache->save($cacheKey, $odds, 86400);

        return $odds;
    }

    /**
     * Força o recálculo e a atualização do cache das odds.
     */
    public function refreshOdds(int $jogoId): array
    {
        Services::cache()->delete('odds_jogo_' . $jogoId);
        return $this->getOdds($jogoId);
    }

    /**
     * Calcula as odds matematicamente.
     */
    private function calculateOddsForGame(int $jogoId, float $baseCasa, float $baseEmpate, float $baseFora): array
    {
        $db = Database::connect();
        
        $query = $db->table('aposta')
            ->select('tipo_escolhido, SUM(valor) as total_valor')
            ->where('jogo_id', $jogoId)
            ->whereIn('status', ['aberta']) // Apenas apostas que afetam a pool
            ->groupBy('tipo_escolhido')
            ->get();

        $apostas = $query->getResultArray();

        $volumes = [
            'vitoria_casa' => 0.0,
            'empate'       => 0.0,
            'vitoria_fora' => 0.0,
        ];

        foreach ($apostas as $aposta) {
            if (isset($volumes[$aposta['tipo_escolhido']])) {
                $volumes[$aposta['tipo_escolhido']] += (float) $aposta['total_valor'];
            }
        }

        $totalPool = $volumes['vitoria_casa'] + $volumes['empate'] + $volumes['vitoria_fora'];
        $poolLiquido = $totalPool * (1 - self::VIGORISH);

        $odds = [
            'casa'   => $totalPool > 0 && $volumes['vitoria_casa'] > 0 ? round($poolLiquido / $volumes['vitoria_casa'], 2) : $baseCasa,
            'empate' => $totalPool > 0 && $volumes['empate'] > 0 ? round($poolLiquido / $volumes['empate'], 2) : $baseEmpate,
            'fora'   => $totalPool > 0 && $volumes['vitoria_fora'] > 0 ? round($poolLiquido / $volumes['vitoria_fora'], 2) : $baseFora,
        ];

        // Garante que a odd mínima sempre seja válida
        $odds['casa'] = max($odds['casa'], 1.01);
        $odds['empate'] = max($odds['empate'], 1.01);
        $odds['fora'] = max($odds['fora'], 1.01);

        return $odds;
    }
}
