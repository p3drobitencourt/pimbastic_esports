<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class CampeonatoRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nome, pais FROM campeonato ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nome, pais FROM campeonato WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    public function create(string $nome, ?string $pais): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO campeonato (nome, pais) VALUES (:nome, :pais)');
        return $stmt->execute([
            ':nome' => $nome,
            ':pais' => $pais !== '' ? $pais : null,
        ]);
    }

    public function update(int $id, string $nome, ?string $pais): bool
    {
        $stmt = $this->pdo->prepare('UPDATE campeonato SET nome = :nome, pais = :pais WHERE id = :id');
        return $stmt->execute([
            ':nome' => $nome,
            ':pais' => $pais !== '' ? $pais : null,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM campeonato WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
