<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Infrastructure\Repositories\ResolucaoRepository;

final class ResolucaoController
{
    public function __construct(private ResolucaoRepository $repo) {}

    public function renderPanel(): array
    {
        return [
            'jogos' => $this->repo->getJogosPendentes()
        ];
    }

    public function liquidarJogo(array $post): void
    {
        $jogoId = (int) $post['jogo_id'];
        $resultado = $post['resultado_real']; // 'casa', 'empate' ou 'fora'

        if ($this->repo->processarResultado($jogoId, $resultado)) {
            header("Location: /resolver.php?success=1");
            exit;
        }
    }
}