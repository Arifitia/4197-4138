<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', static function () {
    return redirect()->to('/auth');
});

// --- Authentification & tableau de bord client (Arifitia) ---
$routes->get('auth', 'AuthController::index');
$routes->post('auth/login', 'AuthController::login');
$routes->get('dashboard', 'DashboardController::index');
$routes->get('dashboard/logout', 'DashboardController::logout');

// --- Préfixes opérateur (Dahmyan) ---
$routes->get('prefixes', 'PrefixeController::index');
$routes->get('prefixes/create', 'PrefixeController::create');
$routes->post('prefixes/store', 'PrefixeController::store');
$routes->get('prefixes/edit/(:num)', 'PrefixeController::edit/$1');
$routes->post('prefixes/update/(:num)', 'PrefixeController::update/$1');
$routes->get('prefixes/delete/(:num)', 'PrefixeController::delete/$1');

// --- Barèmes de frais (Dahmyan) ---
$routes->get('baremes', 'BaremeController::index');
$routes->get('baremes/create', 'BaremeController::create');
$routes->post('baremes/store', 'BaremeController::store');
$routes->get('baremes/edit/(:num)', 'BaremeController::edit/$1');
$routes->post('baremes/update/(:num)', 'BaremeController::update/$1');
$routes->get('baremes/delete/(:num)', 'BaremeController::delete/$1');

// --- Opérations financières (Dahmyan) ---
$routes->post('operations/depot', 'OperationController::depot');
$routes->post('operations/retrait', 'OperationController::retrait');
$routes->post('operations/transfert', 'OperationController::transfert');

// --- Types d'opérations : CRUD opérateur (Arifitia) ---
$routes->get('types-operations', 'TypeOperationController::index');
$routes->get('types-operations/create', 'TypeOperationController::create');
$routes->post('types-operations/store', 'TypeOperationController::store');
$routes->get('types-operations/edit/(:num)', 'TypeOperationController::edit/$1');
$routes->post('types-operations/update/(:num)', 'TypeOperationController::update/$1');
$routes->get('types-operations/delete/(:num)', 'TypeOperationController::delete/$1');

// --- Situation des comptes clients & des gains (Arifitia) ---
$routes->get('operateur/clients', 'OperateurController::clients');
$routes->get('operateur/gains', 'OperateurController::gains');

