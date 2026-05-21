<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Infrastructure\Repositories\ApostaRepository;

final class ApostaController
{
    public function __construct(private ApostaRepository $repo) {}

    public function renderDashboard(int $clienteId): array
    {
        return [
            'jogos' => $this->repo->getMercadoAtivo(),
            'cliente' => $this->repo->getDadosCliente($clienteId),
            'historico' => $this->repo->getHistoricoApostas($clienteId),
            'cliente_id' => $clienteId
        ];
    }

    public function processarAposta(array $post, int $clienteId): void
    {
        $payload = [
            'cliente_id' => $clienteId,
            'jogo_id'    => (int)$post['jogo_id'],
            'valor'      => (float)$post['valor'],
            'tipo'       => $post['tipo_escolhido'],
            'odd'        => (float)$post['odd_escolhida']
        ];

        // Se retornar true (transação commitada), success. Senão, error.
        if ($this->repo->salvarAposta($payload)) {
            header("Location: apostar.php?success=1");
            exit;
        } else {
            header("Location: apostar.php?error=saldo_insuficiente");
            exit;
        }
    }

    public function processarDeposito(array $post, int $clienteId): void
    {
        $valor = (float) ($post['valor_deposito'] ?? 0);

        if ($valor <= 0) {
            header("Location: apostar.php?error=valor_invalido");
            exit;
        }

        if ($this->repo->depositar($clienteId, $valor)) {
            header("Location: apostar.php?success_deposito=1");
            exit;
        }

        header("Location: apostar.php?error=falha_banco");
        exit;
    }
}