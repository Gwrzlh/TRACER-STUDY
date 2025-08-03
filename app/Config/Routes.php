<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes                                                                     
 */




//routes for login
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);


// test-controller
$routes->get('test-db','testController::index');    

// main-route
$routes->get('/', 'Homepage::index');
$routes->get('/admin','adminController::index');                                                                                
$routes->get('/admin/pengguna','penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna','penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post','penggunaController::store');


