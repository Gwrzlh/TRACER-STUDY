<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes untuk login
// $routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);
//route admin
$routes->get('/', 'Homepage::index');
$routes->get('/admin', 'adminController::index');                                                                                
$routes->get('/admin/pengguna', 'penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna', 'penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post', 'penggunaController::store');

$routes->get('/tentang', 'Homepage::tentang');
$routes->get('/kontak', 'Homepage::kontak');
=======



//route ajax 
$routes->group('api', function($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});



