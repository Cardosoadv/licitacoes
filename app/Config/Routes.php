<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();


/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

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
$routes->get('login', '\CodeIgniter\Shield\Controllers\LoginController::loginView', ['as' => 'login']);
$routes->post('login', '\CodeIgniter\Shield\Controllers\LoginController::loginAction');
$routes->get('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerView', ['as' => 'register']);
$routes->post('register', '\CodeIgniter\Shield\Controllers\RegisterController::registerAction');
$routes->get('magic-link', '\CodeIgniter\Shield\Controllers\MagicLinkController::loginView', ['as' => 'magic-link']);
$routes->post('magic-link', '\CodeIgniter\Shield\Controllers\MagicLinkController::loginAction');
$routes->get('logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction', ['as' => 'logout']);
$routes->get('auth/login', 'AuthController::login', ['as' => 'auth-login']);
$routes->get('auth/callback', 'AuthController::callback', ['as' => 'auth-callback']);
$routes->get('auth/logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction', ['as' => 'auth-logout']);
$routes->get('auth/perfil', 'AuthController::perfil', ['as' => 'auth-perfil']);

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
    $routes->post('perfil/alterar-senha', 'Perfil::alterarSenha');
});

/**
 * Handler para requisições CORS preflight (OPTIONS)
 */
$routes->options('(:any)', static function () {
    $response = service('response');
    $response->setStatusCode(200);
    return $response;
});
