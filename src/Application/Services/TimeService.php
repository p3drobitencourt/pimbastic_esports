<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Services;

use PimbasticEsports\Infrastructure\Repositories\TimeRepository;

final class TimeService
{
    public function __construct(private TimeRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(string $nome, string $tecnico, ?string $sigla): void
    {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException('Informe o nome do time.');
        }

        if (trim($tecnico) === '') {
            throw new \InvalidArgumentException('Informe o nome do técnico.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            throw new \InvalidArgumentException('Nome do time deve ter entre 3 e 255 caracteres.');
        }

        if (strlen($tecnico) < 3 || strlen($tecnico) > 255) {
            throw new \InvalidArgumentException('Nome do técnico deve ter entre 3 e 255 caracteres.');
        }

        if ($sigla !== null && strlen($sigla) > 10) {
            throw new \InvalidArgumentException('Sigla não pode exceder 10 caracteres.');
        }

        $this->repository->create(trim($nome), trim($tecnico), $sigla ? trim(strtoupper($sigla)) : null);
    }

    public function update(int $id, string $nome, string $tecnico, ?string $sigla): void
    {
        if (trim($nome) === '') {
            throw new \InvalidArgumentException('Informe o nome do time.');
        }

        if (trim($tecnico) === '') {
            throw new \InvalidArgumentException('Informe o nome do técnico.');
        }

        if (strlen($nome) < 3 || strlen($nome) > 255) {
            throw new \InvalidArgumentException('Nome do time deve ter entre 3 e 255 caracteres.');
        }

        if (strlen($tecnico) < 3 || strlen($tecnico) > 255) {
            throw new \InvalidArgumentException('Nome do técnico deve ter entre 3 e 255 caracteres.');
        }

        if ($sigla !== null && strlen($sigla) > 10) {
            throw new \InvalidArgumentException('Sigla não pode exceder 10 caracteres.');
        }

        $this->repository->update($id, trim($nome), trim($tecnico), $sigla ? trim(strtoupper($sigla)) : null);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
