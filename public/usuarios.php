<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/UsuarioRepository.php';
require_once __DIR__ . '/../src/Application/Services/UsuarioService.php';
require_once __DIR__ . '/../src/Application/Controllers/UsuarioController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\UsuarioRepository;
use PimbasticEsports\Application\Services\UsuarioService;
use PimbasticEsports\Application\Controllers\UsuarioController;

$connector = new DatabaseConnector();
$pdo = $connector->getConnection();

$repository = new UsuarioRepository($pdo);
$service = new UsuarioService($repository);
$controller = new UsuarioController($service);

$controller->handleRequest();
