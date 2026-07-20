<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home route
$routes->get('/', 'Home::index');

// Client routes (login automatique avec numéro de téléphone)
$routes->group('client', function($routes) {
    $routes->get('login', 'Client::login');
    $routes->post('login', 'Client::login');
    $routes->get('logout', 'Client::logout');
    $routes->get('dashboard', 'Client::dashboard');
    $routes->get('balance', 'Client::balance');
    $routes->get('deposit', 'Client::deposit');
    $routes->post('deposit', 'Client::deposit');
    $routes->get('withdraw', 'Client::withdraw');
    $routes->post('withdraw', 'Client::withdraw');
    $routes->get('transfer', 'Client::transfer');
    $routes->post('transfer', 'Client::transfer');
    $routes->get('history', 'Client::history');
});

// Operator routes (administration)
$routes->group('operator', function($routes) {
    $routes->get('dashboard', 'Operator::dashboard');
    $routes->get('prefixes', 'Operator::prefixes');
    $routes->post('prefixes', 'Operator::prefixes');
    $routes->get('operations', 'Operator::operations');
    $routes->post('operations', 'Operator::operations');
    $routes->get('fees', 'Operator::fees');
    $routes->post('fees', 'Operator::fees');
    $routes->get('fees/edit/(:num)', 'Operator::fees_edit/$1');
    $routes->post('fees/edit/(:num)', 'Operator::fees_edit/$1');
    $routes->get('clients', 'Operator::clients');
    $routes->get('transactions', 'Operator::transactions');
    $routes->get('reports', 'Operator::reports');
});
