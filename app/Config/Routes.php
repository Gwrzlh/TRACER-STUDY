<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes                                                                     
 */
<<<<<<< HEAD




//routes for login
=======
>>>>>>> 52c3340 (login yang terbaru)
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
<<<<<<< HEAD
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);


// test-controller
$routes->get('test-db','testController::index');    

// main-route
$routes->get('/', 'Homepage::index');
$routes->get('/admin','adminController::index');                                                                                
$routes->get('/admin/pengguna','penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna','penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post','penggunaController::store');


=======
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']); // optional filter kalau kamu pakai
// $routes->get('/hash-password', 'Auth::hashPasswordManual');
// $routes->get('/generate-hash-admin', 'Auth::generateHashAdmin');
>>>>>>> 52c3340 (login yang terbaru)
