<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

// ── Auth (público) ──────────────────────────────────────────
$routes->post('auth/login', 'AuthController::login');
$routes->post('auth/register', 'AuthController::register');
$routes->post('auth/logout', 'AuthController::logout'); // Mudar para POST via API

// ── Cliente (protegido por AuthFilter + ClienteFilter) ──────
$routes->group('cliente', ['filter' => 'cliente'], static function ($routes) {
    $routes->get('dashboard', 'ClienteController::dashboard');
    $routes->post('saldo', 'ClienteController::adicionarSaldo');
});

// ── Apostas (protegido por AuthFilter + ClienteFilter) ──────
$routes->group('apostas', ['filter' => 'cliente'], static function ($routes) {
    $routes->post('/', 'ApostaController::store');
    $routes->put('(:num)', 'ApostaController::update/$1');
    $routes->delete('(:num)', 'ApostaController::delete/$1');
});

// ── Admin (protegido por AuthFilter + AdminFilter) ──────────
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('jogos-recentes', 'AdminController::indexJogos');

    $routes->get('liquidacao', 'LiquidacaoController::index');
    $routes->get('liquidacao/debug', 'LiquidacaoController::debug');
    $routes->post('liquidacao/processar/(:num)', 'LiquidacaoController::processar/$1');

    // Usando resource() que mapeia index, show, create, update, delete
    // Ignoramos `new` e `edit` pois não usamos mais formulários SSR
    $routes->resource('campeonatos', ['controller' => 'CampeonatoController', 'except' => 'new,edit']);
    $routes->resource('times', ['controller' => 'TimeController', 'except' => 'new,edit']);
    $routes->resource('jogos', ['controller' => 'JogoController', 'except' => 'new,edit']);
    $routes->resource('usuarios', ['controller' => 'UsuarioController', 'except' => 'new,edit']);
});
