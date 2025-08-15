<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------
// ROUTES: Auth/Login
// --------------------
$routes->get('/', 'Homepage::index'); // Default landing
// Landing page Kontak
$routes->get('/kontak', 'Kontak::landing');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
// ini ga kepake
// $routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']);
// $routes->get('/admin', 'adminController::index');
// $routes->get('/admin', 'adminController::index', ['filter' => 'auth']);
//route admin
$routes->get('/', 'Homepage::index');

$routes->get('/admin/pengguna', 'penggunaController::index',);
$routes->get('/admin/pengguna/tambahPengguna', 'penggunaController::create');
$routes->post('/admin/pengguna/tambahPengguna/post', 'penggunaController::store');

$routes->get('admin/dashboard', 'AdminController::dashboard', ['filter' => 'auth']);

//route ajax 
//route organisasi
$routes->get('/admin/tipeorganisasi', 'TipeOrganisasiController::index');
$routes->get('/admin/tipeorganisasi/form', 'TipeOrganisasiController::create');
$routes->post('/admin/tipeorganisasi/insert', 'TipeOrganisasiController::store');


// --------------------
// ROUTES: Admin
// --------------------

//route ajax 
$routes->group('api', function ($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});

$routes->get('/tentang', 'Homepage::tentang');



// --- Import Akun ---
$routes->group('admin/pengguna', function ($routes) {
    $routes->get('', 'PenggunaController::index');
    $routes->get('tambahPengguna', 'PenggunaController::create');
    $routes->post('tambahPengguna/post', 'PenggunaController::store');
    $routes->get('editPengguna/(:num)', 'PenggunaController::edit/$1');
    $routes->post('update/(:num)', 'PenggunaController::update/$1');
    $routes->post('delete/(:num)', 'PenggunaController::delete/$1');

    // Import akun
     $routes->post('import', 'ImportAccount::upload'); 
});

 ---
$routes->group('admin/kontak', function ($routes) {
    $routes->get('', 'Kontak::index');
    $routes->get('create', 'Kontak::create');
    $routes->post('store', 'Kontak::store');
    $routes->get('edit/(:num)', 'Kontak::edit/$1');
    $routes->post('update/(:num)', 'Kontak::update/$1');
    $routes->get('delete/(:num)', 'Kontak::delete/$1');
    $routes->get('deleteKategori/(:segment)', 'Kontak::deletebyKategori/$1');
    $routes->get('getByKategori/(:any)', 'Kontak::getByKategori/$1');
});






// --- Tipe Organisasi ---
$routes->group('admin/tipeorganisasi', function ($routes) {
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
// ROUTES: API (AJAX)  JANGAN DI HAPUS!!!!!!!!
// --------------------
$routes->group('api', function ($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});


// --------------------
// ROUTES: Satuan Organisasi + Nested
// --------------------
$routes->group('satuanorganisasi', ['namespace' => 'App\Controllers'], function ($routes) {

    // Satuan Organisasi - Main
    $routes->get('', 'SatuanOrganisasi::index');
    $routes->get('create', 'SatuanOrganisasi::create');
    $routes->post('store', 'SatuanOrganisasi::store');
    $routes->get('edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('delete/(:num)', 'SatuanOrganisasi::delete/$1');

    // Jurusan - Nested
    $routes->group('jurusan', function ($routes) {
        $routes->get('', 'Jurusan::index');
        $routes->get('create', 'Jurusan::create');
        $routes->post('store', 'Jurusan::store');
        $routes->get('edit/(:num)', 'Jurusan::edit/$1');
        $routes->post('update/(:num)', 'Jurusan::update/$1');
        $routes->post('delete/(:num)', 'Jurusan::delete/$1');
    });

    // Prodi - Nested
    $routes->group('prodi', function ($routes) {
        $routes->get('', 'ProdiController::index');
        $routes->get('create', 'ProdiController::create');
        $routes->post('store', 'ProdiController::store');
        $routes->get('edit/(:num)', 'ProdiController::edit/$1');
        $routes->post('update/(:num)', 'ProdiController::update/$1');
        $routes->post('delete/(:num)', 'ProdiController::delete/$1');
    });
});

// questionair route
$routes->group('admin', ['namespace' => 'App\Controllers'], function ($routes) {

    // Main Questionnaire CRUD
    $routes->get('questionnaire', 'QuestionnairController::index');                    // List all questionnaires
    $routes->get('questionnaire/create', 'QuestionnairController::create');            // Show create form
    $routes->post('questionnaire/store', 'QuestionnairController::store');             // Store new questionnaire
    $routes->get('questionnaire/(:num)', 'QuestionnairController::show/$1');           // Show single questionnaire
    $routes->get('questionnaire/(:num)/edit', 'QuestionnairController::edit/$1');      // Edit questionnaire form
    $routes->post('questionnaire/(:num)/update', 'QuestionnairController::update/$1'); // Update questionnaire
    $routes->post('questionnaire/(:num)/delete', 'QuestionnairController::delete/$1'); // Delete questionnaire

    // Toggle questionnaire status
    $routes->post('questionnaire/(:num)/toggle-status', 'QuestionnairController::toggleStatus/$1');

    // Questions Management Routes
    $routes->get('questionnaire/(:num)/questions', 'QuestionnairController::manageQuestions/$1');           // List questions
    $routes->get('questionnaire/(:num)/questions/create', 'QuestionnairController::createQuestion/$1');     // Create question form
    $routes->post('questionnaire/(:num)/questions/store', 'QuestionnairController::storeQuestion/$1');      // Store question
    $routes->get('questionnaire/(:num)/questions/(:num)/edit', 'QuestionnairController::editQuestion/$1/$2'); // Edit question form
    $routes->post('questionnaire/(:num)/questions/(:num)/update', 'QuestionnairController::updateQuestion/$1/$2'); // Update question
    $routes->post('questionnaire/(:num)/questions/(:num)/delete', 'QuestionnairController::deleteQuestion/$1/$2'); // Delete question

    // Question ordering (drag & drop)
    $routes->post('questionnaire/(:num)/questions/reorder', 'QuestionnairController::reorderQuestions/$1');

    // Question options management
    $routes->get('questions/(:num)/options', 'QuestionnairController::manageOptions/$1');                   // Manage question options
    $routes->post('questions/(:num)/options/store', 'QuestionnairController::storeOption/$1');             // Store option
    $routes->post('questions/options/(:num)/update', 'QuestionnairController::updateOption/$1');           // Update option
    $routes->post('questions/options/(:num)/delete', 'QuestionnairController::deleteOption/$1');           // Delete option

    // Preview & Testing
    $routes->get('questionnaire/(:num)/preview', 'QuestionnairController::preview/$1');                    // Preview questionnaire
    $routes->get('questionnaire/(:num)/test', 'QuestionnairController::test/$1');                          // Test questionnaire as alumni

    // Analytics & Reports
    $routes->get('questionnaire/(:num)/responses', 'QuestionnairController::responses/$1');                // View responses
    $routes->get('questionnaire/(:num)/analytics', 'QuestionnairController::analytics/$1');               // Analytics dashboard
    $routes->get('questionnaire/(:num)/export', 'QuestionnairController::exportResponses/$1');            // Export responses to Excel/CSV

    // Bulk actions
    $routes->post('questionnaire/bulk-delete', 'QuestionnairController::bulkDelete');                      // Bulk delete questionnaires
    $routes->post('questionnaire/bulk-status', 'QuestionnairController::bulkStatus');                     // Bulk change status

    // Pengaturan Situs
    $routes->post('pengaturan-situs/simpan', 'PengaturanSitus::simpan');
});

// Route Alumni
$routes->get('alumni/login', 'AlumniController::login');
$routes->post('alumni/login', 'AlumniController::doLogin');
$routes->get('alumni/dashboard', 'AlumniController::dashboard');
$routes->get('alumni/logout', 'AlumniController::logout');
$routes->get('alumni', 'AlumniController::index');
$routes->get('alumnisurveyor', 'AlumniController::surveyor');







$routes->get('/pengaturan-situs', 'PengaturanSitus::index'); // halaman pengaturan
$routes->post('/pengaturan-situs/simpan', 'PengaturanSitus::simpan'); // proses simpan
