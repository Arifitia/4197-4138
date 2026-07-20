<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
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

