<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/CampeonatoRepository.php';
require_once __DIR__ . '/../src/Application/Services/CampeonatoService.php';
require_once __DIR__ . '/../src/Application/Controllers/CampeonatoController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\CampeonatoRepository;
use PimbasticEsports\Application\Services\CampeonatoService;
use PimbasticEsports\Application\Controllers\CampeonatoController;

$connector = new DatabaseConnector();
$pdo = $connector->getConnection();

$repository = new CampeonatoRepository($pdo);
$service = new CampeonatoService($repository);
$controller = new CampeonatoController($service);

$controller->handleRequest();
