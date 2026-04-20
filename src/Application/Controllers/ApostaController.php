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

        if ($this->repo->salvarAposta($payload)) {
            header("Location: /apostar.php?success=1");
            exit;
        }
    }
}