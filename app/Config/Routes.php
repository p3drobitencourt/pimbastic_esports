<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Auth (público) ──────────────────────────────────────────
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login/autenticar', 'AuthController::autenticar');
$routes->get('cadastro', 'AuthController::cadastro');
$routes->post('cadastro/salvar', 'AuthController::salvar');
$routes->get('logout', 'AuthController::logout');

// ── Cliente (protegido por AuthFilter + ClienteFilter) ──────
$routes->group('cliente', static function ($routes) {
    $routes->get('sportsbook', 'ClienteController::sportsbook');
    $routes->post('apostar', 'ClienteController::apostar');
});

// ── Admin (protegido por AuthFilter + AdminFilter) ──────────
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');

    // Campeonatos CRUD
    $routes->get('campeonatos', 'CampeonatoController::index');
    $routes->get('campeonatos/create', 'CampeonatoController::create');
    $routes->post('campeonatos/store', 'CampeonatoController::store');
    $routes->get('campeonatos/edit/(:num)', 'CampeonatoController::edit/$1');
    $routes->post('campeonatos/update/(:num)', 'CampeonatoController::update/$1');

    // Times CRUD
    $routes->get('times', 'TimeController::index');
    $routes->get('times/create', 'TimeController::create');
    $routes->post('times/store', 'TimeController::store');
    $routes->get('times/edit/(:num)', 'TimeController::edit/$1');
    $routes->post('times/update/(:num)', 'TimeController::update/$1');

    // Jogos CRUD
    $routes->get('jogos', 'JogoController::index');
    $routes->get('jogos/create', 'JogoController::create');
    $routes->post('jogos/store', 'JogoController::store');
    $routes->get('jogos/edit/(:num)', 'JogoController::edit/$1');
    $routes->post('jogos/update/(:num)', 'JogoController::update/$1');

    // Usuários CRUD
    $routes->get('usuarios', 'UsuarioController::index');
    $routes->get('usuarios/create', 'UsuarioController::create');
    $routes->post('usuarios/store', 'UsuarioController::store');
    $routes->get('usuarios/edit/(:num)', 'UsuarioController::edit/$1');
    $routes->post('usuarios/update/(:num)', 'UsuarioController::update/$1');
});
