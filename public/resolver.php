<?php
declare(strict_types=1);

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: formularios/login.php?feedback_type=error&feedback_message=Acesso negado.");
    exit;
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/ResolucaoRepository.php';
require_once __DIR__ . '/../src/Application/Controllers/ResolucaoController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\ResolucaoRepository;
use PimbasticEsports\Application\Controllers\ResolucaoController;

$pdo = (new DatabaseConnector())->getConnection();
$controller = new ResolucaoController(new ResolucaoRepository($pdo));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->liquidarJogo($_POST);
}

$viewData = $controller->renderPanel();
require __DIR__ . '/../views/admin/resolucao.phtml';