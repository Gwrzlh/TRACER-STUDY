<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes untuk login
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);
//route admin
$routes->get('/home', 'Homepage::index');
$routes->get('/admin', 'adminController::index');                                                                                
$routes->get('/admin/pengguna', 'penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna', 'penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post', 'penggunaController::store');
<<<<<<< HEAD
    
=======

>>>>>>> 1539460399782589446333fa41746bc2621b5bc8
