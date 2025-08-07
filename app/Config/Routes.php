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
$routes->get('/admin', 'adminController::index', ['filter' => 'auth']);
$routes->get('/admin/pengguna', 'penggunaController::index');
$routes->get('/admin/pengguna/tambahPengguna', 'penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post', 'penggunaController::store');

//route organisasi
$routes->get('/admin/tipeorganisasi', 'TipeOrganisasiController::index');
$routes->get('/admin/tipeorganisasi/form', 'TipeOrganisasiController::create');
$routes->post('/admin/tipeorganisasi/insert', 'TipeOrganisasiController::store');

$routes->get('/admin/welcome-page', 'AdminWelcomePage::index'); // jika kamu punya filter role
$routes->post('/admin/welcome-page/update', 'AdminWelcomePage::update');



//route ajax 
$routes->group('api', function($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});




$routes->get('/tentang', 'Homepage::tentang');
$routes->get('/kontak', 'Homepage::kontak');


// Group namespace dan prefix 'satuanorganisasi'
$routes->group('satuanorganisasi', ['namespace' => 'App\Controllers'], function ($routes) {
    // Halaman utama tab (gabungan)
    $routes->get('', 'SatuanOrganisasi::index');
// Route untuk satuan organisasi
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('satuanorganisasi', 'SatuanOrganisasi::index');
    $routes->get('satuanorganisasi/create', 'SatuanOrganisasi::create');
    $routes->post('satuanorganisasi/store', 'SatuanOrganisasi::store');
    $routes->get('satuanorganisasi/edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('satuanorganisasi/update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('satuanorganisasi/delete/(:num)', 'SatuanOrganisasi::delete/$1');
});
    // CRUD Satuan Organisasi
    $routes->get('create', 'SatuanOrganisasi::create');
    $routes->post('store', 'SatuanOrganisasi::store');
    $routes->get('edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('delete/(:num)', 'SatuanOrganisasi::delete/$1');

    // CRUD Jurusan (nested di dalam satuanorganisasi/jurusan)
    $routes->group('jurusan', function ($routes) {
        $routes->get('', 'Jurusan::index');
        $routes->get('create', 'Jurusan::create');
        $routes->post('store', 'Jurusan::store');
        $routes->get('edit/(:num)', 'Jurusan::edit/$1');
        $routes->post('update/(:num)', 'Jurusan::update/$1');
        $routes->post('delete/(:num)', 'Jurusan::delete/$1');
    });

    // CRUD Prodi (nested di dalam satuanorganisasi/prodi)
    $routes->group('prodi', function ($routes) {
        $routes->get('', 'ProdiController::index');
        $routes->get('create', 'ProdiController::create');
        $routes->post('store', 'ProdiController::store');
        $routes->get('edit/(:num)', 'ProdiController::edit/$1');
        $routes->post('update/(:num)', 'ProdiController::update/$1');
        $routes->post('delete/(:num)', 'ProdiController::delete/$1');
    });
});
