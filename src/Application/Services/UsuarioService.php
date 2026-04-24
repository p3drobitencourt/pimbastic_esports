<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Services;

use PimbasticEsports\Infrastructure\Repositories\UsuarioRepository;

final class UsuarioService
{
    public function __construct(private UsuarioRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function create(string $nome, string $email, string $senha, string $perfil): void
    {
        if (trim($nome) === '' || trim($email) === '' || trim($senha) === '') {
            throw new \InvalidArgumentException('Nome, e-mail e senha são obrigatórios.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido.');
        }

        $perfisValidos = ['admin', 'cliente'];
        if (!in_array($perfil, $perfisValidos, true)) {
            throw new \InvalidArgumentException('Perfil inválido.');
        }

        // Verifica se o e-mail já está em uso
        $existente = $this->repository->buscarPorEmail($email);
        if ($existente !== null) {
            throw new \InvalidArgumentException('Já existe um usuário com este e-mail.');
        }
        
        $sucesso = $this->repository->cadastrar(trim($nome), trim($email), trim($senha), $perfil);
        if (!$sucesso) {
            throw new \RuntimeException('Erro ao cadastrar usuário.');
        }
    }

    public function update(int $id, string $nome, string $email, string $perfil, ?string $senha = null): void
    {
        if (trim($nome) === '' || trim($email) === '') {
            throw new \InvalidArgumentException('Nome e e-mail são obrigatórios.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido.');
        }

        $perfisValidos = ['admin', 'cliente'];
        if (!in_array($perfil, $perfisValidos, true)) {
            throw new \InvalidArgumentException('Perfil inválido.');
        }

        // Verifica se o e-mail está em uso por outro id
        $existente = $this->repository->buscarPorEmail($email);
        if ($existente !== null && (int) $existente['id'] !== $id) {
            throw new \InvalidArgumentException('Já existe um outro usuário com este e-mail.');
        }

        $senhaOpcional = ($senha !== null && trim($senha) !== '') ? trim($senha) : null;

        $this->repository->update($id, trim($nome), trim($email), $perfil, $senhaOpcional);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
