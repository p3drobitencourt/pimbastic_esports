<?php
declare(strict_types=1);

session_start();

// Verifica se o usuário está logado e se é um cliente
if (!isset($_SESSION['logado']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../src/Infrastructure/Database/DatabaseConnector.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/ApostaRepository.php';
require_once __DIR__ . '/../src/Application/Controllers/ApostaController.php';
require_once __DIR__ . '/../src/Infrastructure/Repositories/UsuarioRepository.php';
require_once __DIR__ . '/../src/Application/Controllers/AuthController.php';

use PimbasticEsports\Infrastructure\Database\DatabaseConnector;
use PimbasticEsports\Infrastructure\Repositories\ApostaRepository;
use PimbasticEsports\Application\Controllers\ApostaController;
use PimbasticEsports\Infrastructure\Repositories\UsuarioRepository;
use PimbasticEsports\Application\Controllers\AuthController;

$pdo = (new DatabaseConnector())->getConnection();

// Listener de Logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $authController = new AuthController(new UsuarioRepository($pdo));
    $authController->logout();
    exit;
}

$controller = new ApostaController(new ApostaRepository($pdo));
$clienteId = (int) $_SESSION['cliente_id'];

// Multiplexador de requisições POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'aposta';

    if ($action === 'deposito') {
        $controller->processarDeposito($_POST, $clienteId);
    } else {
        $controller->processarAposta($_POST, $clienteId);
    }
}

$viewData = $controller->renderDashboard($clienteId);
require __DIR__ . '/../views/cliente/sportsbook.phtml';