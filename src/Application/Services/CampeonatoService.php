<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Services;

use PimbasticEsports\Infrastructure\Repositories\CampeonatoRepository;

final class CampeonatoService
{
    public function __construct(private CampeonatoRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(string $nome, ?string $pais): void
    {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException('Informe o nome do campeonato.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            throw new \InvalidArgumentException('Nome do campeonato deve ter entre 3 e 255 caracteres.');
        }

        if ($pais !== null && strlen($pais) > 100) {
            throw new \InvalidArgumentException('País não pode exceder 100 caracteres.');
        }

        $this->repository->create(trim($nome), $pais ? trim($pais) : null);
    }

    public function update(int $id, string $nome, ?string $pais): void
    {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException('Informe o nome do campeonato.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            throw new \InvalidArgumentException('Nome do campeonato deve ter entre 3 e 255 caracteres.');
        }

        if ($pais !== null && strlen($pais) > 100) {
            throw new \InvalidArgumentException('País não pode exceder 100 caracteres.');
        }

        $this->repository->update($id, trim($nome), $pais ? trim($pais) : null);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
