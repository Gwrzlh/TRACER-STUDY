<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------
// ROUTES: Auth/Login
// --------------------
$routes->get('/', 'Homepage::index'); // Default landing
$routes->get('/kontak', 'TracerStudy::kontak');
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

$routes->get('admin/dashboard', 'AdminController::dashboard');

//route ajax 
//route organisasi
$routes->get('/admin/tipeorganisasi','TipeOrganisasiController::index');
$routes->get('/admin/tipeorganisasi/form','TipeOrganisasiController::create');
$routes->post('/admin/tipeorganisasi/insert','TipeOrganisasiController::store');


// --------------------
// ROUTES: Admin
// --------------------
$routes->get('/admin', 'adminController::index');
//route ajax 
$routes->group('api', function($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});

$routes->get('/tentang', 'Homepage::tentang');
$routes->get('/kontak', 'Homepage::kontak');

// --- Pengguna ---
$routes->group('admin/pengguna', function ($routes) {
    $routes->get('', 'penggunaController::index');
    $routes->get('tambahPengguna', 'penggunaController::create');
    $routes->post('tambahPengguna/post', 'penggunaController::store');
    $routes->get('editPengguna/(:num)', 'penggunaController::edit/$1');
    $routes->post('update/(:num)', 'penggunaController::update/$1');
    $routes->post('delete/(:num)', 'penggunaController::delete/$1');
});
// --- Kontak ---
$routes->group('admin/kontak', function ($routes) {
    $routes->get('', 'KontakController::index');
    $routes->get('create', 'KontakController::create');
    $routes->post('store', 'KontakController::store');
    $routes->get('edit/(:num)', 'KontakController::edit/$1');
    $routes->post('update/(:num)', 'KontakController::update/$1');
    $routes->post('delete/(:num)', 'KontakController::delete/$1');
});

// --- Kontak Deskripsi ---
$routes->group('admin/kontak-deskripsi', function ($routes) {
    $routes->get('', 'KontakDeskripsiController::index');
    $routes->get('edit', 'KontakDeskripsiController::index'); // bisa juga
    $routes->post('update/(:num)', 'KontakDeskripsiController::update/$1');
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
  $routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {

    // === Questionnaire Management ===
    
    $routes->group('questionnaire', function($routes) {
        $routes->get('/', 'QuestionnairController::index');
        $routes->get('create', 'QuestionnairController::create');
        $routes->post('store', 'QuestionnairController::store');
        $routes->get('(:num)', 'QuestionnairController::show/$1');
        $routes->get('(:num)/edit', 'QuestionnairController::edit/$1');
        $routes->post('(:num)/update', 'QuestionnairController::update/$1');
        $routes->get('(:num)/delete', 'QuestionnairController::delete/$1');
        $routes->post('(:num)/toggle-status', 'QuestionnairController::toggleStatus/$1');
        $routes->get('(:num)/preview', 'QuestionnairController::preview/$1');
    });

    // === Page Management ===

    $routes->group('questionnaire/(:num)/pages', function($routes) {
        $routes->get('/', 'QuestionnairePageController::index/$1');
        $routes->get('create', 'QuestionnairePageController::create/$1');
        $routes->post('store', 'QuestionnairePageController::store/$1');
        $routes->get('(:num)/edit', 'QuestionnairePageController::edit/$1/$2');
        $routes->post('(:num)/update', 'QuestionnairePageController::update/$1/$2');
        $routes->post('(:num)/delete', 'QuestionnairePageController::delete/$1/$2');
    });

    // === Question Management - FIX: Semua route harus explicit ===

    // $routes->group('questionnaire/(:num)/pages/(:num)/questions', function($routes) {
    //     $routes->get('/', 'QuestionnairController::manageQuestions/$1/$2');
    //     $routes->get('create', 'QuestionnairController::createQuestion/$1/$2');
    //     $routes->post('store', 'QuestionnairController::storeQuestion/$1/$2');
    //     $routes->get('(:num)/edit', 'QuestionnairController::editQuestion/$1/$2/$3');
    //     $routes->post('(:num)/update', 'QuestionnairController::updateQuestion/$1/$2/$3');
    //     $routes->post('(:num)/delete', 'QuestionnairController::deleteQuestion/$1/$2/$3');
    //     $routes->post('reorder', 'QuestionnairController::reorderQuestions/$1/$2');
    // });

    // FIX: Tambahan route untuk section (siap untuk future implementation)
    
    $routes->group('questionnaire/(:num)/pages/(:num)/sections', function($routes) {

    $routes->get('/', 'SectionController::index/$1/$2');
    $routes->get('create', 'SectionController::create/$1/$2');
    $routes->post('store', 'SectionController::store/$1/$2');
    $routes->get('(:num)/edit', 'SectionController::edit/$1/$2/$3');
    $routes->post('(:num)/update', 'SectionController::update/$1/$2/$3');
    $routes->post('(:num)/delete', 'SectionController::delete/$1/$2/$3');

    // Questions per section

    $routes->get('(:num)/questions', 'QuestionnairController::manageSectionQuestions/$1/$2/$3');
    $routes->get('(:num)/questions/create', 'QuestionnairController::createSectionQuestion/$1/$2/$3');
    $routes->post('(:num)/questions/store', 'QuestionnairController::storeInlineQuestion/$1/$2/$3');
    $routes->get('(:num)/questions/(:num)/edit', 'QuestionnairController::editSectionQuestion/$1/$2/$3/$4');
    $routes->post('(:num)/questions/(:num)/update', 'QuestionnairController::updateSectionQuestion/$1/$2/$3/$4');
    $routes->post('(:num)/questions/(:num)/delete', 'QuestionnairController::deleteSectionQuestion/$1/$2/$3/$4');

 });

    // === Option Management ===

    $routes->group('questions/(:num)/options', function($routes) {
        $routes->get('/', 'QuestionnaireController::manageOptions/$1');
        $routes->post('store', 'QuestionnaireController::storeOption/$1');
        $routes->post('(:num)/update', 'QuestionnaireController::updateOption/$1');
        $routes->post('(:num)/delete', 'QuestionnaireController::deleteOption/$1');
    });

    // === Condition Management ===
    $routes->group('questions/(:num)/conditions', function($routes) {
        $routes->get('/', 'QuestionnaireConditionController::index/$1');
        $routes->get('create', 'QuestionnaireConditionController::create/$1');
        $routes->post('store', 'QuestionnaireConditionController::store/$1');
        $routes->get('(:num)/edit', 'QuestionnaireConditionController::edit/$1/$2');
        $routes->post('(:num)/update', 'QuestionnaireConditionController::update/$1/$2');
        $routes->post('(:num)/delete', 'QuestionnaireConditionController::delete/$1/$2');
    });

});


// Route Alumni
$routes->get('alumni/login', 'Alumni::login');
$routes->post('alumni/login', 'Alumni::doLogin');
$routes->get('alumni/dashboard', 'Alumni::dashboard');
$routes->get('alumni/logout', 'Alumni::logout');

// Pengaturan Situs
$routes->get('/pengaturan-situs', 'PengaturanSitus::index');

// ajax conditional_logic 

$routes->get('/admin/get-conditional-options', 'QuestionnairController::getConditionalOptions', ['as' => 'admin.questioner.getOptions']);
// app/Config/Routes.php
$routes->get('admin/questionnaire/pages/get-question-options', 'QuestionnairePageController::getQuestionOptions');

// Di Routes.php tambahkan:
$routes->get('admin/questionnaire/(:num)/questions/(:num)/options', 'QuestionController::getQuestionOptions/$1/$2');
$routes->get('admin/questionnaire/(:num)/pages/(:num)/sections/(:num)/questions-with-options', 'QuestionController::getQuestionsWithOptions/$1/$2/$3');












