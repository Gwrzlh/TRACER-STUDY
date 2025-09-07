<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================
// PUBLIC ROUTES / AUTH
// ===============================
$routes->get('/', 'Homepage::index');
$routes->get('/home', 'LandingPage::home');

$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');

$routes->get('/lupapassword', 'Auth::forgotPassword');
$routes->post('/lupapassword', 'Auth::sendResetLink');
$routes->get('/resetpassword/(:any)', 'Auth::resetPassword/$1');
$routes->post('/resetpassword', 'Auth::doResetPassword');

// ===============================
// LANDING PAGE / PUBLIC
// ===============================
$routes->get('/kontak', 'Kontak::landing');
$routes->get('/laporan', 'AdminLaporan::showAll');         // default tahun terbaru
$routes->get('/laporan/(:num)', 'AdminLaporan::showAll/$1');

// ===============================
// ADMIN ROUTES
// ===============================
$routes->group('admin', ['namespace' => 'App\Controllers', 'filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('welcome-page', 'AdminWelcomePage::index');
    $routes->post('welcome-page/update', 'AdminWelcomePage::update');

    // Pengguna
    $routes->group('pengguna', function ($routes) {
        $routes->get('', 'PenggunaController::index');
        $routes->get('tambahPengguna', 'PenggunaController::create');
        $routes->post('tambahPengguna/post', 'PenggunaController::store');
        $routes->get('editPengguna/(:num)', 'PenggunaController::edit/$1');
        $routes->post('update/(:num)', 'PenggunaController::update/$1');
        $routes->post('delete/(:num)', 'PenggunaController::delete/$1');
        $routes->post('import', 'ImportAccount::upload');
    });

    // Kontak
    $routes->group('kontak', function ($routes) {
        $routes->get('', 'Kontak::index');
        $routes->get('search', 'Kontak::search');
        $routes->post('store', 'Kontak::store');
        $routes->post('store-multiple', 'Kontak::storeMultiple');
        $routes->post('delete/(:num)', 'Kontak::delete/$1');
    });

    // Tipe Organisasi
    $routes->group('tipeorganisasi', function ($routes) {
        $routes->get('', 'TipeOrganisasiController::index');
        $routes->get('form', 'TipeOrganisasiController::create');
        $routes->post('insert', 'TipeOrganisasiController::store');
        $routes->get('edit/(:num)', 'TipeOrganisasiController::edit/$1');
        $routes->post('edit/update/(:num)', 'TipeOrganisasiController::update/$1');
        $routes->post('delete/(:num)', 'TipeOrganisasiController::delete/$1');
    });

    // Tentang
    $routes->get('tentang', 'Tentang::index');
    $routes->get('tentang/edit', 'Tentang::edit');
    $routes->post('tentang/update', 'Tentang::update');

    // Laporan
    $routes->group('laporan', function ($routes) {
        $routes->get('', 'AdminLaporan::index');
        $routes->get('create', 'AdminLaporan::create');
        $routes->post('save', 'AdminLaporan::save');
        $routes->get('edit/(:num)', 'AdminLaporan::edit/$1');
        $routes->post('update/(:num)', 'AdminLaporan::update/$1');
    });

    // Respon
    $routes->group('respon', function ($routes) {
        $routes->get('', 'AdminRespon::index');
    });

    // Email Template
    $routes->get('emailtemplate', 'AdminEmailTemplateController::index');
    $routes->post('emailtemplate/update/(:num)', 'AdminEmailTemplateController::update/$1');

    // Questionnaire / Kuesioner
    $routes->group('questionnaire', function ($routes) {
        $routes->get('', 'QuestionnairController::index');
        $routes->get('create', 'QuestionnairController::create');
        $routes->post('store', 'QuestionnairController::store');
        $routes->get('(:num)', 'QuestionnairController::show/$1');
        $routes->get('(:num)/edit', 'QuestionnairController::edit/$1');
        $routes->post('(:num)/update', 'QuestionnairController::update/$1');
        $routes->get('(:num)/delete', 'QuestionnairController::delete/$1');
        $routes->post('(:num)/toggle-status', 'QuestionnairController::toggleStatus/$1');
        $routes->get('(:num)/preview', 'QuestionnairController::preview/$1');
    });
});

// ===============================
// KAPRODI ROUTES
// ===============================
$routes->group('kaprodi', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'KaprodiController::dashboard');
    $routes->get('supervisi', 'KaprodiController::supervisi');
    $routes->get('questioner', 'KaprodiController::questioner');
    $routes->get('akreditasi', 'KaprodiController::akreditasi');
    $routes->get('ami', 'KaprodiController::ami');
    $routes->get('profil', 'KaprodiController::profil');
});

/// ===============================
// ALUMNI ROUTES
// ===============================
$routes->group('alumni', ['filter' => 'auth'], function ($routes) {

    // -------------------------------
    // Dashboard
    // -------------------------------
    $routes->get('/', 'AlumniController::dashboard');
    $routes->get('dashboard', 'AlumniController::dashboard');

    // -------------------------------
    // Profil Alumni Biasa
    // -------------------------------
    $routes->get('profil', 'AlumniController::profil');
    $routes->get('profil/edit', 'AlumniController::editProfil');
    $routes->post('profil/update', 'AlumniController::updateProfil');
    $routes->post('updateFoto/(:num)', 'AlumniController::updateFoto/$1');

    // -------------------------------
    // Profil & Supervisi Alumni Surveyor
    // -------------------------------
    $routes->group('surveyor', function ($routes) {
        $routes->get('profil', fn() => (new \App\Controllers\AlumniController())->profil('surveyor'));


        // Kuesioner Surveyor
        $routes->get('questionnaires', 'AlumniController::questionnairesForSurveyor');
        $routes->get('questionnaire/(:num)', 'AlumniController::fillQuestionnaire/$1');
        $routes->post('questionnaire/submit', 'AlumniController::submitAnswers');
    });

    // -------------------------------
    // Pesan & Notifikasi
    // -------------------------------
    $routes->get('notifikasi', 'AlumniController::notifikasi');
    $routes->get('notifikasi/tandai/(:num)', 'AlumniController::tandaiDibaca/$1');
    $routes->get('notifikasi/hapus/(:num)', 'AlumniController::hapusNotifikasi/$1');
    $routes->get('notifikasi/count', 'AlumniController::getNotifCount');
    $routes->get('pesan/(:num)', 'AlumniController::pesan/$1');
    $routes->post('kirimPesanManual', 'AlumniController::kirimPesanManual');
    $routes->get('viewpesan/(:num)', 'AlumniController::viewPesan/$1');

    // -------------------------------
    // Lihat teman
    // -------------------------------
    $routes->get('lihat_teman', 'AlumniController::lihatTeman');
    $routes->get('supervisi', 'AlumniController::supervisi');

    // -------------------------------
    // Questionnaires / Kuesioner Alumni Biasa
    // -------------------------------
    $routes->get('questionnaires', 'AlumniController::questionnairesForAlumni');
    $routes->get('questionnaire/(:num)', 'AlumniController::fillQuestionnaire/$1');
    $routes->post('questionnaire/submit', 'AlumniController::submitAnswers');
});
