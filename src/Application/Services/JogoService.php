<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Services;

use PimbasticEsports\Infrastructure\Repositories\JogoRepository;

final class JogoService
{
    public function __construct(private JogoRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(int $campeonatoId, int $timeCasaId, int $timeForaId, string $dataHorario, float $oddCasa, float $oddEmpate, float $oddFora): void
    {
        if ($campeonatoId <= 0 || $timeCasaId <= 0 || $timeForaId <= 0 || trim($dataHorario) === '') {
            throw new \InvalidArgumentException('Preencha todos os campos obrigatórios do jogo.');
        }

        if ($timeCasaId === $timeForaId) {
            throw new \InvalidArgumentException('Selecione times diferentes para casa e fora.');
        }

        if ($oddCasa <= 0 || $oddEmpate <= 0 || $oddFora <= 0) {
            throw new \InvalidArgumentException('As odds devem ser valores positivos.');
        }

        $formattedDataHorario = str_replace('T', ' ', trim($dataHorario));
        if (strlen($formattedDataHorario) === 16) {
            $formattedDataHorario .= ':00';
        }

        $this->repository->create(
            $campeonatoId,
            $timeCasaId,
            $timeForaId,
            $formattedDataHorario,
            (float) number_format($oddCasa, 2, '.', ''),
            (float) number_format($oddEmpate, 2, '.', ''),
            (float) number_format($oddFora, 2, '.', '')
        );
    }

    public function update(int $id, int $campeonatoId, int $timeCasaId, int $timeForaId, string $dataHorario, float $oddCasa, float $oddEmpate, float $oddFora): void
    {
        if ($campeonatoId <= 0 || $timeCasaId <= 0 || $timeForaId <= 0 || trim($dataHorario) === '') {
            throw new \InvalidArgumentException('Preencha todos os campos obrigatórios do jogo.');
        }

        if ($timeCasaId === $timeForaId) {
            throw new \InvalidArgumentException('Selecione times diferentes para casa e fora.');
        }

        if ($oddCasa <= 0 || $oddEmpate <= 0 || $oddFora <= 0) {
            throw new \InvalidArgumentException('As odds devem ser valores positivos.');
        }

        $formattedDataHorario = str_replace('T', ' ', trim($dataHorario));
        if (strlen($formattedDataHorario) === 16) {
            $formattedDataHorario .= ':00';
        }

        $this->repository->update(
            $id,
            $campeonatoId,
            $timeCasaId,
            $timeForaId,
            $formattedDataHorario,
            (float) number_format($oddCasa, 2, '.', ''),
            (float) number_format($oddEmpate, 2, '.', ''),
            (float) number_format($oddFora, 2, '.', '')
        );
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
