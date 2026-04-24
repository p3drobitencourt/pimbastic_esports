<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Infrastructure\Repositories\UsuarioRepository;

final class AuthController
{
    public function __construct(private UsuarioRepository $repo) {}

    public function processarLogin(string $email, string $senha): void
{
    $usuario = $this->repo->buscarPorEmail($email);

    // Validação com password_verify conforme o slide de PDS
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $_SESSION['logado'] = true;
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['perfil']; 
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['cliente_id'] = $usuario['cliente_id'];

        // LÓGICA DE REDIRECIONAMENTO POR PERFIL
        if ($usuario['perfil'] === 'admin') {
            // Admin vai para o Dashboard Geral
            header("Location: index.php"); 
        } else {
            // Cliente vai para a tela de apostas (apostar.php)
            // Certifique-se de que o arquivo public/apostar.php existe
            header("Location: apostar.php"); 
        }
        exit;
    }

    // Se falhar, volta para o login com erro
    header("Location: login.php?erro=1");
    exit;
}

   public function processarCadastro(string $nome, string $email, string $senha, string $perfil): void
    {
        $sucesso = $this->repo->cadastrar($nome, $email, $senha, $perfil);
        
        if ($sucesso) {
            header("Location: login.php?sucesso=1");
        } else {
            header("Location: cadastro.php?erro=falha_no_banco");
        }
        exit;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    public function validarDados(string $email, string $senha): ?string 
    {
        if (empty($email) || empty($senha)) {
            return "Todos os campos são obrigatórios.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "O formato do e-mail é inválido.";
        }
        return null; // Tudo ok
    }
}