<?php

namespace App\Services;

use App\Models\ApostaModel;
use App\Models\ClienteModel;
use App\Models\JogoModel;

class ApostaService
{
    public function __construct(
        private readonly ApostaModel $apostaModel = new ApostaModel(),
        private readonly ClienteModel $clienteModel = new ClienteModel(),
        private readonly JogoModel $jogoModel = new JogoModel()
    ) {
    }

    public function dashboard(int $clienteId): array
    {
        return [
            'cliente' => [
                'saldo_carteira' => $this->clienteModel->getSaldoAtual($clienteId),
            ],
            'jogos' => $this->jogoModel->getJogosAtivos(),
            'historico' => $this->apostaModel->getMinhasApostasComDetalhes($clienteId),
            'apostas' => $this->apostaModel->getMinhasApostasComDetalhes($clienteId),
            'resumo' => $this->apostaModel->getResumoCarteira($clienteId),
        ];
    }

    public function registrar(int $clienteId, array $dados): array
    {
        $jogo = $this->jogoModel->find((int) $dados['jogo_id']);

        if (!$jogo || strtotime($jogo['data_horario']) <= strtotime('+30 minutes')) {
            return ['success' => false, 'message' => 'Apostas encerradas para este jogo. O prazo limite é 30 minutos antes da partida.'];
        }

        $oddMap = [
            'casa' => ['campo' => 'odd_casa', 'tipo' => 'vitoria_casa'],
            'empate' => ['campo' => 'odd_empate', 'tipo' => 'empate'],
            'fora' => ['campo' => 'odd_fora', 'tipo' => 'vitoria_fora'],
        ];

        $tipo = $dados['tipo'] ?? '';
        if (!isset($oddMap[$tipo])) {
            return ['success' => false, 'message' => 'Tipo de aposta inválido.'];
        }

        $oddEscolhida = (float) $jogo[$oddMap[$tipo]['campo']];

        return $this->apostaModel->registrarApostaTransacional([
            'cliente_id' => $clienteId,
            'jogo_id' => (int) $dados['jogo_id'],
            'valor' => (float) $dados['valor'],
            'tipo_escolhido' => $oddMap[$tipo]['tipo'],
            'odd_escolhida' => $oddEscolhida,
        ]);
    }

    public function atualizar(int $apostaId, int $clienteId, array $dados): array
    {
        $jogo = $this->jogoModel->find((int) $dados['jogo_id']);

        if (!$jogo || strtotime($jogo['data_horario']) <= strtotime('+30 minutes')) {
            return ['success' => false, 'message' => 'Apostas encerradas para este jogo. O prazo limite é 30 minutos antes da partida.'];
        }

        $oddMap = [
            'casa' => ['campo' => 'odd_casa', 'tipo' => 'vitoria_casa'],
            'empate' => ['campo' => 'odd_empate', 'tipo' => 'empate'],
            'fora' => ['campo' => 'odd_fora', 'tipo' => 'vitoria_fora'],
        ];

        $tipo = $dados['tipo'] ?? '';
        if (!isset($oddMap[$tipo])) {
            return ['success' => false, 'message' => 'Tipo de aposta inválido.'];
        }

        return $this->apostaModel->atualizarApostaTransacional($apostaId, $clienteId, [
            'valor' => (float) $dados['valor'],
            'tipo_escolhido' => $oddMap[$tipo]['tipo'],
            'odd_escolhida' => (float) $jogo[$oddMap[$tipo]['campo']],
        ]);
    }

    public function cancelar(int $apostaId, int $clienteId): array
    {
        return $this->apostaModel->cancelarApostaTransacional($apostaId, $clienteId);
    }
}