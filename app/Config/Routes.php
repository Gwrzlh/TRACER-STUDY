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

//route organisasi
$routes->get('/admin/tipeorganisasi','TipeOrganisasiController::index');
$routes->get('/admin/tipeorganisasi/form','TipeOrganisasiController::create');
$routes->post('/admin/tipeorganisasi/insert','TipeOrganisasiController::store');



//route ajax 
$routes->group('api', function ($routes) {
// Add your API routes here
});
$routes->get('/tentang', 'Homepage::tentang');
$routes->get('/kontak', 'Homepage::kontak');

// Route untuk satuan organisasi
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('satuanorganisasi', 'SatuanOrganisasi::index');
    $routes->get('satuanorganisasi/create', 'SatuanOrganisasi::create');
    $routes->post('satuanorganisasi/store', 'SatuanOrganisasi::store');
    $routes->get('satuanorganisasi/edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('satuanorganisasi/update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('satuanorganisasi/delete/(:num)', 'SatuanOrganisasi::delete/$1');
});

 





