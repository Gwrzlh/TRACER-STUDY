<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------
// ROUTES: Auth/Login
// --------------------
$routes->get('/', 'Homepage::index'); // Default landing
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);


// --------------------
// ROUTES: Admin
// --------------------
$routes->get('/admin', 'adminController::index');

// --- Pengguna ---
$routes->group('admin/pengguna', function($routes) {
    $routes->get('', 'penggunaController::index');
    $routes->get('tambahPengguna', 'penggunaController::create');
    $routes->post('tambahPengguna/post', 'penggunaController::store');
    $routes->get('editPengguna/(:num)', 'penggunaController::edit/$1');
    $routes->post('update/(:num)', 'penggunaController::update/$1');
    $routes->post('delete/(:num)', 'penggunaController::delete/$1');
});

// --- Tipe Organisasi ---
$routes->group('admin/tipeorganisasi', function($routes) {
    $routes->get('', 'TipeOrganisasiController::index');
    $routes->get('form', 'TipeOrganisasiController::create');
    $routes->post('insert', 'TipeOrganisasiController::store');
    $routes->get('edit/(:num)', 'TipeOrganisasiController::edit/$1');
    $routes->post('edit/update/(:num)', 'TipeOrganisasiController::update/$1');
    $routes->post('delete/(:num)', 'TipeOrganisasiController::delete/$1');
});

// --- Tentang ---
$routes->get('/tentang', 'Tentang::index');
$routes->get('/admin/tentang/edit', 'Tentang::edit');
$routes->post('/admin/tentang/update', 'Tentang::update');

// --- Welcome Page Admin ---
$routes->get('/admin/welcome-page', 'AdminWelcomePage::index');
$routes->post('/admin/welcome-page/update', 'AdminWelcomePage::update');


// --------------------
// ROUTES: Landing
// --------------------
$routes->get('/home', 'LandingPage::home');


// --------------------
// ROUTES: API (AJAX)
// --------------------
$routes->group('api', function($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});


// --------------------
// ROUTES: Satuan Organisasi + Nested
// --------------------
$routes->group('satuanorganisasi', ['namespace' => 'App\Controllers'], function($routes) {

    // Satuan Organisasi - Main
    $routes->get('', 'SatuanOrganisasi::index');
    $routes->get('create', 'SatuanOrganisasi::create');
    $routes->post('store', 'SatuanOrganisasi::store');
    $routes->get('edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('delete/(:num)', 'SatuanOrganisasi::delete/$1');

    // Jurusan - Nested
    $routes->group('jurusan', function($routes) {
        $routes->get('', 'Jurusan::index');
        $routes->get('create', 'Jurusan::create');
        $routes->post('store', 'Jurusan::store');
        $routes->get('edit/(:num)', 'Jurusan::edit/$1');
        $routes->post('update/(:num)', 'Jurusan::update/$1');
        $routes->post('delete/(:num)', 'Jurusan::delete/$1');
    });

    // Prodi - Nested
    $routes->group('prodi', function($routes) {
        $routes->get('', 'ProdiController::index');
        $routes->get('create', 'ProdiController::create');
        $routes->post('store', 'ProdiController::store');
        $routes->get('edit/(:num)', 'ProdiController::edit/$1');
        $routes->post('update/(:num)', 'ProdiController::update/$1');
        $routes->post('delete/(:num)', 'ProdiController::delete/$1');
    });

});
