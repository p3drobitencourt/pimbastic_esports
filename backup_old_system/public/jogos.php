<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/JogoRepository.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/CampeonatoRepository.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/TimeRepository.php';
require_once __DIR__ . '/../src/Application/Services/JogoService.php';
require_once __DIR__ . '/../src/Application/Services/CampeonatoService.php';
require_once __DIR__ . '/../src/Application/Services/TimeService.php';
require_once __DIR__ . '/../src/Application/Controllers/JogoController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\JogoRepository;
use PimbasticEsports\Infrastructure\Repositories\CampeonatoRepository;
use PimbasticEsports\Infrastructure\Repositories\TimeRepository;
use PimbasticEsports\Application\Services\JogoService;
use PimbasticEsports\Application\Services\CampeonatoService;
use PimbasticEsports\Application\Services\TimeService;
use PimbasticEsports\Application\Controllers\JogoController;

$connector = new DatabaseConnector();
$pdo = $connector->getConnection();

$campeonatoRepo = new CampeonatoRepository($pdo);
$campeonatoService = new CampeonatoService($campeonatoRepo);
$timeRepo = new TimeRepository($pdo);
$timeService = new TimeService($timeRepo);

$repository = new JogoRepository($pdo);
$service = new JogoService($repository);
$controller = new JogoController($service, $campeonatoService, $timeService);

$controller->handleRequest();
