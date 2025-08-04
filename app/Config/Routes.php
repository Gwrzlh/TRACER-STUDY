<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes                                                                     
 */

// test-controller
$routes->get('test-db','testController::index');    

// main-route
$routes->get('/', 'Homepage::index');
$routes->get('/admin','adminController::index');                                                                                
$routes->get('/admin/pengguna','penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna','penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post','penggunaController::store');

//ajax route
$routes->group('api', function($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});

