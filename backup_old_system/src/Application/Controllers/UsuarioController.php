<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Application\Services\UsuarioService;

final class UsuarioController
{
    public function __construct(private UsuarioService $service) {}

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'index';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        try {
            switch ($action) {
                case 'index':
                    $this->index();
                    break;
                case 'create':
                    $this->create();
                    break;
                case 'store':
                    $this->store();
                    break;
                case 'edit':
                    if ($id) $this->edit($id);
                    else $this->redirectWithMessage('index', 'ID inválido', 'error');
                    break;
                case 'update':
                    if ($id) $this->update($id);
                    else $this->redirectWithMessage('index', 'ID inválido', 'error');
                    break;
                case 'delete':
                    if ($id) $this->delete($id);
                    else $this->redirectWithMessage('index', 'ID inválido', 'error');
                    break;
                default:
                    $this->index();
                    break;
            }
        } catch (\Exception $e) {
            if (in_array($action, ['store', 'update'])) {
               if ($action === 'store') $this->create($e->getMessage());
               else $this->edit($id, $e->getMessage());
            } else {
               $this->redirectWithMessage('index', $e->getMessage(), 'error');
            }
        }
    }

    private function index(): void
    {
        $usuarios = $this->service->getAll();
        require dirname(__DIR__, 3) . '/views/admin/usuarios/index.phtml';
    }

    private function create(?string $errorMsg = null): void
    {
        $usuario = null; // null for creation
        require dirname(__DIR__, 3) . '/views/admin/usuarios/form.phtml';
    }

    private function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $perfil = $_POST['perfil'] ?? 'cliente';

        $this->service->create($nome, $email, $senha, $perfil);
        $this->redirectWithMessage('index', 'Usuário criado com sucesso!', 'success');
    }

    private function edit(int $id, ?string $errorMsg = null): void
    {
        $usuario = $this->service->getById($id);
        if (!$usuario) {
            $this->redirectWithMessage('index', 'Usuário não encontrado', 'error');
        }
        require dirname(__DIR__, 3) . '/views/admin/usuarios/form.phtml';
    }

    private function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? null;
        $perfil = $_POST['perfil'] ?? 'cliente';

        $this->service->update($id, $nome, $email, $perfil, $senha);
        $this->redirectWithMessage('index', 'Usuário atualizado com sucesso!', 'success');
    }

    private function delete(int $id): void
    {
        // Avoid deleting oneself
        if (isset($_SESSION['id']) && (int)$_SESSION['id'] === $id) {
            $this->redirectWithMessage('index', 'Não é possível deletar seu próprio usuário', 'error');
        }

        $this->service->delete($id);
        $this->redirectWithMessage('index', 'Usuário deletado com sucesso!', 'success');
    }

    private function redirectWithMessage(string $action, string $message, string $type): never
    {
        $url = "?action={$action}&message=" . urlencode($message) . "&type=" . urlencode($type);
        header("Location: {$url}");
        exit;
    }
}
