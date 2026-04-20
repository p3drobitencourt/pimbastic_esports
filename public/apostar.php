<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/ApostaRepository.php';
require_once __DIR__ . '/../src/Application/Controllers/ApostaController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\ApostaRepository;
use PimbasticEsports\Application\Controllers\ApostaController;

$pdo = (new DatabaseConnector())->getConnection();
$controller = new ApostaController(new ApostaRepository($pdo));

$clienteId = 1; // Mock de sessão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processarAposta($_POST, $clienteId);
}

$viewData = $controller->renderDashboard($clienteId);
require __DIR__ . '/../views/cliente/sportsbook.phtml';