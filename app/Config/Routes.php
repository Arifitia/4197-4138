<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static function () {
    return redirect()->to('/auth');
});

// --- Espace client ---
$routes->get('auth', 'AuthController::index');
$routes->post('auth/login', 'AuthController::login');
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'client']);
$routes->get('dashboard/logout', 'DashboardController::logout');
$routes->get('historique', 'DashboardController::historique', ['filter' => 'client']);

// --- Opérations financières client ---
$routes->post('operations/depot', 'OperationController::depot', ['filter' => 'client']);
$routes->post('operations/retrait', 'OperationController::retrait', ['filter' => 'client']);
$routes->post('operations/transfert', 'OperationController::transfert', ['filter' => 'client']);
$routes->post('operations/bulkTransfert', 'OperationController::bulkTransfert', ['filter' => 'client']);

// --- Espace opérateur ---
$routes->get('operateur/auth', 'OperateurAuthController::index');
$routes->post('operateur/auth/login', 'OperateurAuthController::login');
$routes->get('operateur/auth/logout', 'OperateurAuthController::logout');
$routes->get('operateur/dashboard', 'OperateurController::dashboard', ['filter' => 'operateur']);
$routes->get('operateur/configuration', 'ConfigurationController::index', ['filter' => 'operateur']);
$routes->post('operateur/configuration', 'ConfigurationController::update', ['filter' => 'operateur']);

// --- Routes opérateur protégées ---
$routes->get('prefixes', 'PrefixeController::index', ['filter' => 'operateur']);
$routes->get('prefixes/create', 'PrefixeController::create', ['filter' => 'operateur']);
$routes->post('prefixes/store', 'PrefixeController::store', ['filter' => 'operateur']);
$routes->get('prefixes/edit/(:num)', 'PrefixeController::edit/$1', ['filter' => 'operateur']);
$routes->post('prefixes/update/(:num)', 'PrefixeController::update/$1', ['filter' => 'operateur']);
$routes->get('prefixes/delete/(:num)', 'PrefixeController::delete/$1', ['filter' => 'operateur']);

$routes->get('baremes', 'BaremeController::index', ['filter' => 'operateur']);
$routes->get('baremes/create', 'BaremeController::create', ['filter' => 'operateur']);
$routes->post('baremes/store', 'BaremeController::store', ['filter' => 'operateur']);
$routes->get('baremes/edit/(:num)', 'BaremeController::edit/$1', ['filter' => 'operateur']);
$routes->post('baremes/update/(:num)', 'BaremeController::update/$1', ['filter' => 'operateur']);
$routes->get('baremes/delete/(:num)', 'BaremeController::delete/$1', ['filter' => 'operateur']);

$routes->get('types-operations', 'TypeOperationController::index', ['filter' => 'operateur']);
$routes->get('types-operations/create', 'TypeOperationController::create', ['filter' => 'operateur']);
$routes->post('types-operations/store', 'TypeOperationController::store', ['filter' => 'operateur']);
$routes->get('types-operations/edit/(:num)', 'TypeOperationController::edit/$1', ['filter' => 'operateur']);
$routes->post('types-operations/update/(:num)', 'TypeOperationController::update/$1', ['filter' => 'operateur']);
$routes->get('types-operations/delete/(:num)', 'TypeOperationController::delete/$1', ['filter' => 'operateur']);

$routes->get('operateur/clients', 'OperateurController::clients', ['filter' => 'operateur']);
$routes->get('operateur/gains', 'OperateurController::gains', ['filter' => 'operateur']);
