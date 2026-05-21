<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Infrastructure\Repositories\ClienteApostaRepository;

final class ClienteController
{
    public function __construct(private ClienteApostaRepository $repository) {}

    public function index(int $clienteId): array
    {
        return [
            'cliente_id' => $clienteId,
            'jogos' => $this->repository->obterJogosDisponiveis()
        ];
    }

    public function apostar(array $post, int $clienteId): void
    {
        $jogoId = (int) ($post['jogo_id'] ?? 0);
        $valor = (float) ($post['valor'] ?? 0);
        $tipo = (string) ($post['tipo_escolhido'] ?? '');
        $odd = (float) ($post['odd_escolhida'] ?? 0);

        if ($jogoId <= 0 || $valor <= 0 || empty($tipo) || $odd <= 0) {
            header("Location: /cliente.php?cliente_id={$clienteId}&error=Dados inválidos");
            exit;
        }

        $this->repository->registrarAposta($clienteId, $jogoId, $valor, $tipo, $odd);
        
        header("Location: /cliente.php?cliente_id={$clienteId}&success=Aposta registrada");
        exit;
    }
}