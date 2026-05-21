<?php
declare(strict_types=1);

namespace PimbasticEsports\Infrastructure\Repositories;

use PDO;

final class UsuarioRepository
{
    public function __construct(private PDO $pdo) {}

    // Busca o usuário para o Login
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $usuario ?: null;
    }

    public function cadastrar(string $nome, string $email, string $senha, string $perfil): bool
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        // Separação de domínio por perfil
        if ($perfil === 'cliente') {
            $this->pdo->beginTransaction();
            
            try {
                // 1. Cria a entidade raiz (Cliente)
                $stmtCli = $this->pdo->prepare("INSERT INTO cliente (nome, saldo_carteira) VALUES (:nome, 0.00)");
                $stmtCli->execute([':nome' => $nome]);
                
                $clienteId = (int) $this->pdo->lastInsertId();

                // 2. Cria a credencial com a Foreign Key
                $stmtUsu = $this->pdo->prepare(
                    "INSERT INTO usuario (nome, email, senha, perfil, cliente_id) 
                     VALUES (:nome, :email, :senha, :perfil, :fk)"
                );
                $stmtUsu->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $hash,
                    ':perfil' => $perfil,
                    ':fk' => $clienteId
                ]);

                $this->pdo->commit();
                return true;
                
            } catch (\PDOException $e) {
                $this->pdo->rollBack();
                error_log("Falha de transação ACID: " . $e->getMessage());
                return false;
            }
        }

        // Fallback: Cadastro isolado para perfil 'admin' (sem dependência de carteira)
        $stmt = $this->pdo->prepare("INSERT INTO usuario (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)");
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $hash,
            ':perfil' => $perfil
        ]);
    }

    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nome, email, perfil, cliente_id FROM usuario ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, nome, email, perfil, cliente_id FROM usuario WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    public function update(int $id, string $nome, string $email, string $perfil, ?string $senha = null): bool
    {
        if ($senha) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare('UPDATE usuario SET nome = :nome, email = :email, perfil = :perfil, senha = :senha WHERE id = :id');
            return $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':perfil' => $perfil,
                ':senha' => $hash,
                ':id' => $id
            ]);
        }

        $stmt = $this->pdo->prepare('UPDATE usuario SET nome = :nome, email = :email, perfil = :perfil WHERE id = :id');
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':perfil' => $perfil,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}