<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/TimeRepository.php';
require_once __DIR__ . '/../src/Application/Services/TimeService.php';
require_once __DIR__ . '/../src/Application/Controllers/TimeController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\TimeRepository;
use PimbasticEsports\Application\Services\TimeService;
use PimbasticEsports\Application\Controllers\TimeController;

$connector = new DatabaseConnector();
$pdo = $connector->getConnection();

$repository = new TimeRepository($pdo);
$service = new TimeService($repository);
$controller = new TimeController($service);

$controller->handleRequest();
