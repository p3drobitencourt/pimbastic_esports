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
}