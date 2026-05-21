<?php
declare(strict_types=1);

namespace PimbasticEsports\Application\Controllers;

use PimbasticEsports\Application\Services\JogoService;
use PimbasticEsports\Application\Services\CampeonatoService;
use PimbasticEsports\Application\Services\TimeService;

final class JogoController
{
    public function __construct(
        private JogoService $service,
        private CampeonatoService $campeonatoService,
        private TimeService $timeService
    ) {}

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
               // Render form again with error
               if ($action === 'store') $this->create($e->getMessage());
               else $this->edit($id, $e->getMessage());
            } else {
               $this->redirectWithMessage('index', $e->getMessage(), 'error');
            }
        }
    }

    private function index(): void
    {
        $jogos = $this->service->getAll();
        require dirname(__DIR__, 3) . '/views/admin/jogos/index.phtml';
    }

    private function create(?string $errorMsg = null): void
    {
        $jogo = null; // null for creation
        $campeonatos = $this->campeonatoService->getAll();
        $times = $this->timeService->getAll();
        require dirname(__DIR__, 3) . '/views/admin/jogos/form.phtml';
    }

    private function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $campeonatoId = isset($_POST['campeonato_id']) ? (int)$_POST['campeonato_id'] : 0;
        $timeCasaId = isset($_POST['time_casa_id']) ? (int)$_POST['time_casa_id'] : 0;
        $timeForaId = isset($_POST['time_fora_id']) ? (int)$_POST['time_fora_id'] : 0;
        $dataHorario = $_POST['data_horario'] ?? '';
        $oddCasa = isset($_POST['odd_casa']) ? (float)$_POST['odd_casa'] : 0.0;
        $oddEmpate = isset($_POST['odd_empate']) ? (float)$_POST['odd_empate'] : 0.0;
        $oddFora = isset($_POST['odd_fora']) ? (float)$_POST['odd_fora'] : 0.0;

        $this->service->create($campeonatoId, $timeCasaId, $timeForaId, $dataHorario, $oddCasa, $oddEmpate, $oddFora);
        $this->redirectWithMessage('index', 'Jogo criado com sucesso!', 'success');
    }

    private function edit(int $id, ?string $errorMsg = null): void
    {
        $jogo = $this->service->getById($id);
        if (!$jogo) {
            $this->redirectWithMessage('index', 'Jogo não encontrado', 'error');
        }
        
        $campeonatos = $this->campeonatoService->getAll();
        $times = $this->timeService->getAll();
        require dirname(__DIR__, 3) . '/views/admin/jogos/form.phtml';
    }

    private function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithMessage('index', 'Método inválido', 'error');
        }

        $campeonatoId = isset($_POST['campeonato_id']) ? (int)$_POST['campeonato_id'] : 0;
        $timeCasaId = isset($_POST['time_casa_id']) ? (int)$_POST['time_casa_id'] : 0;
        $timeForaId = isset($_POST['time_fora_id']) ? (int)$_POST['time_fora_id'] : 0;
        $dataHorario = $_POST['data_horario'] ?? '';
        $oddCasa = isset($_POST['odd_casa']) ? (float)$_POST['odd_casa'] : 0.0;
        $oddEmpate = isset($_POST['odd_empate']) ? (float)$_POST['odd_empate'] : 0.0;
        $oddFora = isset($_POST['odd_fora']) ? (float)$_POST['odd_fora'] : 0.0;

        $this->service->update($id, $campeonatoId, $timeCasaId, $timeForaId, $dataHorario, $oddCasa, $oddEmpate, $oddFora);
        $this->redirectWithMessage('index', 'Jogo atualizado com sucesso!', 'success');
    }

    private function delete(int $id): void
    {
        $this->service->delete($id);
        $this->redirectWithMessage('index', 'Jogo deletado com sucesso!', 'success');
    }

    private function redirectWithMessage(string $action, string $message, string $type): never
    {
        $url = "?action={$action}&message=" . urlencode($message) . "&type=" . urlencode($type);
        header("Location: {$url}");
        exit;
    }
}
