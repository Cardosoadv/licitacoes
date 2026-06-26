<?php

namespace App\Routes;

use Config\Services;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Home routes
$routes->get('/', 'HomeController::index');
$routes->get('dashboard', 'HomeController::dashboard');
$routes->get('estatisticas', 'HomeController::estatisticas');

// Licitações routes
$routes->get('licitacoes', 'LicitacaoController::index');
$routes->get('licitacoes/listar', 'LicitacaoController::listar');
$routes->get('licitacoes/detalhes/(:num)', 'LicitacaoController::detalhes/$1');
$routes->get('licitacoes/buscar', 'LicitacaoController::buscar');
$routes->get('licitacoes/filtrar', 'LicitacaoController::filtrar');
$routes->get('licitacoes/estatisticas', 'LicitacaoController::estatisticas');
$routes->get('licitacoes/exportar', 'LicitacaoController::exportarCSV');

// Alertas routes
$routes->group('alertas', function($routes) {
    $routes->get('/', 'AlertaController::index');
    $routes->get('listar', 'AlertaController::listar');
    $routes->get('criar', 'AlertaController::criar');
    $routes->post('store', 'AlertaController::store');
    $routes->get('editar/(:num)', 'AlertaController::editar/$1');
    $routes->post('update/(:num)', 'AlertaController::update/$1');
    $routes->delete('delete/(:num)', 'AlertaController::delete/$1');
    $routes->post('toggle/(:num)', 'AlertaController::toggle/$1');
});

// Auth routes
$routes->get('auth/login', 'AuthController::login');
$routes->get('auth/callback', 'AuthController::callback');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('auth/perfil', 'AuthController::perfil');

// Notificações routes
$routes->group('notificacoes', function($routes) {
    $routes->get('/', 'NotificacaoController::index');
    $routes->post('marcar-lida/(:num)', 'NotificacaoController::marcarLida/$1');
    $routes->post('marcar-todas-lidas', 'NotificacaoController::marcarTodasLidas');
    $routes->get('contar', 'NotificacaoController::contarNaoLidas');
});

// API routes
$routes->group('api', function($routes) {
    $routes->get('licitacoes', 'ApiController::licitacoes');
    $routes->get('licitacoes/(:num)', 'ApiController::licitacao/$1');
    $routes->get('estatisticas', 'ApiController::estatisticas');
    $routes->get('alertas', 'ApiController::alertas');
    $routes->post('alertas', 'ApiController::criarAlerta');
    $routes->get('notificacoes', 'ApiController::notificacoes');
});

