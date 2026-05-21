<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Application\Services\CampeonatoService;

final class CampeonatoController
{
    public function __construct(private CampeonatoService $service) {}

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
            $this->redirectWithMessage('index', $e->getMessage(), 'error');
        }
    }

    private function index(): void
    {
        $campeonatos = $this->service->getAll();
        require dirname(__DIR__, 3) . '/views/admin/campeonatos/index.phtml';
    }

    private function create(): void
    {
        $campeonato = null; // null for creation
        require dirname(__DIR__, 3) . '/views/admin/campeonatos/form.phtml';
    }

    private function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $nome = $_POST['nome'] ?? '';
        $pais = $_POST['pais'] ?? '';

        $this->service->create($nome, $pais !== '' ? $pais : null);
        $this->redirectWithMessage('index', 'Campeonato criado com sucesso!', 'success');
    }

    private function edit(int $id): void
    {
        $campeonato = $this->service->getById($id);
        if (!$campeonato) {
            $this->redirectWithMessage('index', 'Campeonato não encontrado', 'error');
        }
        require dirname(__DIR__, 3) . '/views/admin/campeonatos/form.phtml';
    }

    private function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $nome = $_POST['nome'] ?? '';
        $pais = $_POST['pais'] ?? '';

        $this->service->update($id, $nome, $pais !== '' ? $pais : null);
        $this->redirectWithMessage('index', 'Campeonato atualizado com sucesso!', 'success');
    }

    private function delete(int $id): void
    {
        $this->service->delete($id);
        $this->redirectWithMessage('index', 'Campeonato deletado com sucesso!', 'success');
    }

    private function redirectWithMessage(string $action, string $message, string $type): never
    {
        $url = "?action={$action}&message=" . urlencode($message) . "&type=" . urlencode($type);
        header("Location: {$url}");
        exit;
    }
}
