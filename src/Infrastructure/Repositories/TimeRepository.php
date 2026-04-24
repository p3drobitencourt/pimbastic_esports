<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class TimeRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nome, tecnico, sigla FROM time ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nome, tecnico, sigla FROM time WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    public function create(string $nome, string $tecnico, ?string $sigla): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO time (nome, tecnico, sigla) VALUES (:nome, :tecnico, :sigla)');
        return $stmt->execute([
            ':nome' => $nome,
            ':tecnico' => $tecnico,
            ':sigla' => $sigla !== '' ? $sigla : null,
        ]);
    }

    public function update(int $id, string $nome, string $tecnico, ?string $sigla): bool
    {
        $stmt = $this->pdo->prepare('UPDATE time SET nome = :nome, tecnico = :tecnico, sigla = :sigla WHERE id = :id');
        return $stmt->execute([
            ':nome' => $nome,
            ':tecnico' => $tecnico,
            ':sigla' => $sigla !== '' ? $sigla : null,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM time WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
