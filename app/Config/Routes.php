<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

// ── Auth (público) ──────────────────────────────────────────
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login/autenticar', 'AuthController::autenticar');
$routes->get('cadastro', 'AuthController::cadastro');
$routes->post('cadastro/salvar', 'AuthController::salvar');
$routes->get('logout', 'AuthController::logout');

// ── Cliente (protegido por AuthFilter + ClienteFilter) ──────
$routes->group('cliente', ['filter' => 'cliente'], static function ($routes) {
    $routes->get('/', 'ClienteController::dashboard');
    $routes->get('dashboard', 'ClienteController::dashboard');
    $routes->get('sportsbook', 'ClienteController::sportsbook');
        $routes->get('carteira/adicionar', 'ClienteController::showAdicionarSaldo');
    $routes->post('apostar', 'ClienteController::apostar');
        $routes->post('carteira/adicionar', 'ClienteController::adicionarSaldo');
    $routes->put('atualizar-aposta/(:num)', 'ClienteController::atualizarAposta/$1');
    $routes->post('atualizar-aposta/(:num)', 'ClienteController::atualizarAposta/$1');
    $routes->delete('cancelar-aposta/(:num)', 'ClienteController::cancelarAposta/$1');
    $routes->post('cancelar-aposta/(:num)', 'ClienteController::cancelarAposta/$1');
    $routes->get('carteira', 'ClienteController::carteira');
});

// ── Admin (protegido por AuthFilter + AdminFilter) ──────────
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('jogos', 'AdminController::indexJogos');
    $routes->get('liquidacao', 'LiquidacaoController::index');
    $routes->get('liquidacao/debug', 'LiquidacaoController::debug');
    $routes->post('liquidacao/processar/(:num)', 'LiquidacaoController::processar/$1');

    // Campeonatos CRUD
    $routes->get('campeonatos', 'CampeonatoController::index');
    $routes->get('campeonatos/create', 'CampeonatoController::create');
    $routes->post('campeonatos/store', 'CampeonatoController::store');
    $routes->get('campeonatos/edit/(:num)', 'CampeonatoController::edit/$1');
    $routes->post('campeonatos/update/(:num)', 'CampeonatoController::update/$1');
    $routes->put('campeonatos/update/(:num)', 'CampeonatoController::update/$1');
    $routes->delete('campeonatos/delete/(:num)', 'CampeonatoController::delete/$1');

    // Times CRUD
    $routes->get('times', 'TimeController::index');
    $routes->get('times/create', 'TimeController::create');
    $routes->post('times/store', 'TimeController::store');
    $routes->get('times/edit/(:num)', 'TimeController::edit/$1');
    $routes->post('times/update/(:num)', 'TimeController::update/$1');
    $routes->put('times/update/(:num)', 'TimeController::update/$1');
    $routes->delete('times/delete/(:num)', 'TimeController::delete/$1');

    // Jogos CRUD
    $routes->get('jogos/create', 'JogoController::create');
    $routes->post('jogos/store', 'JogoController::store');
    $routes->get('jogos/edit/(:num)', 'JogoController::edit/$1');
    $routes->post('jogos/update/(:num)', 'JogoController::update/$1');
    $routes->put('jogos/update/(:num)', 'JogoController::update/$1');
    $routes->delete('jogos/delete/(:num)', 'JogoController::delete/$1');

    // Usuários CRUD
    $routes->get('usuarios', 'UsuarioController::index');
    $routes->get('usuarios/create', 'UsuarioController::create');
    $routes->post('usuarios/store', 'UsuarioController::store');
    $routes->get('usuarios/edit/(:num)', 'UsuarioController::edit/$1');
    $routes->post('usuarios/update/(:num)', 'UsuarioController::update/$1');
    $routes->put('usuarios/update/(:num)', 'UsuarioController::update/$1');
    $routes->delete('usuarios/delete/(:num)', 'UsuarioController::delete/$1');
});
