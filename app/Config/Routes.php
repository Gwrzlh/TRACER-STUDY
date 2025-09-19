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


// admin pengguna
$routes->group('admin/pengguna', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('', 'PenggunaController::index');
    $routes->get('tambahPengguna', 'PenggunaController::create');
    $routes->post('tambahPengguna/post', 'PenggunaController::store');
    $routes->get('editPengguna/(:num)', 'PenggunaController::edit/$1');
    $routes->post('update/(:num)', 'PenggunaController::update/$1');
    $routes->delete('delete/(:num)', 'PenggunaController::delete/$1');

    // ✅ Import akun (cukup tulis 'import')
    $routes->get('import', 'ImportAccount::form');
    $routes->post('import', 'ImportAccount::process');
});
// ===============================
// ADMIN ROUTES
// ===============================
$routes->group('admin', ['filter' => 'adminAuth'], function ($routes) {

    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('welcome-page', 'AdminWelcomePage::index');
    $routes->post('welcome-page/update', 'AdminWelcomePage::update');

    // Pengguna
    // $routes->group('pengguna', function ($routes) {
    //     $routes->get('', 'PenggunaController::index');
    //     $routes->get('tambahPengguna', 'PenggunaController::create');
    //     $routes->post('tambahPengguna/post', 'PenggunaController::store');
    //     $routes->get('editPengguna/(:num)', 'PenggunaController::edit/$1');
    //     $routes->post('update/(:num)', 'PenggunaController::update/$1');
    //     $routes->post('delete/(:num)', 'PenggunaController::delete/$1');
    //     $routes->post('import', 'ImportAccount::upload');
    // });
    // Kontak
    $routes->group('kontak', function ($routes) {
        $routes->get('', 'Kontak::index');
        $routes->get('search', 'Kontak::search');
        $routes->post('store', 'Kontak::store');
        $routes->post('store-multiple', 'Kontak::storeMultiple');
        $routes->post('delete/(:num)', 'Kontak::delete/$1');
    });

    // --------------------
    // ROUTES: Admin
    // --------------------

    //route ajax 
    $routes->group('api', function ($routes) {
        $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
    });

    // ================== Kontak ==================
    $routes->get('admin/kontak', 'Kontak::index');           // Halaman index kontak
    $routes->get('admin/kontak/search', 'Kontak::search');   // AJAX Search
    $routes->post('admin/kontak/store', 'Kontak::store');    // Tambah kontak
    $routes->post('admin/kontak/delete/(:num)', 'Kontak::delete/$1'); // Hapus kontak
    $routes->post('admin/kontak/store-multiple', 'Kontak::storeMultiple'); // Tambah kontak multiple

    // Opsional (Landing Page publik, jika dibutuhkan)
    // $routes->get('kontak', 'Kontak::landing');

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
    $routes->get('tentang', 'Tentang::index');
    $routes->get('tentang', 'Tentang::index');
    $routes->get('admin/tentang/edit', 'Tentang::edit');
    $routes->post('admin/tentang/update', 'Tentang::update');

    // Tipe Organisasi
    $routes->group('tipeorganisasi', function ($routes) {
        $routes->get('', 'TipeOrganisasiController::index');
        $routes->get('form', 'TipeOrganisasiController::create');
        $routes->post('insert', 'TipeOrganisasiController::store');
        $routes->get('edit/(:num)', 'TipeOrganisasiController::edit/$1');
        $routes->post('edit/update/(:num)', 'TipeOrganisasiController::update/$1');
        $routes->post('delete/(:num)', 'TipeOrganisasiController::delete/$1');
    });
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

    // $routes->group('respon', function ($routes) {
    //     $routes->get('', 'AdminRespon::index');
    //     $routes->get('export', 'AdminRespon::exportExcel');
    // });


    $routes->group('log_activities', function ($routes) {
        $routes->get('/', 'LogController::index');
        $routes->get('export', 'LogController::export');
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
        $routes->get('pages/getQuestionOptions', 'QuestionnairePageController::getQuestionOptions');
    });



    // === Page Management ===

    $routes->group('questionnaire/(:num)/pages', function ($routes) {
        $routes->get('/', 'QuestionnairePageController::index/$1');
        $routes->get('create', 'QuestionnairePageController::create/$1');
        $routes->post('store', 'QuestionnairePageController::store/$1');
        $routes->get('(:num)/edit', 'QuestionnairePageController::edit/$1/$2');
        $routes->post('(:num)/update', 'QuestionnairePageController::update/$1/$2');
        // $routes->get('getQuestionOptions','QuestionnairePageController::getQuestionOptions');
        $routes->get('(:num)/delete', 'QuestionnairePageController::delete/$1/$2');
        // $routes->get('')
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

    $routes->group('questionnaire/(:num)/pages/(:num)/sections', function ($routes) {

        $routes->get('/', 'SectionController::index/$1/$2');
        $routes->get('create', 'SectionController::create/$1/$2');
        $routes->post('store', 'SectionController::store/$1/$2');
        $routes->get('(:num)/edit', 'SectionController::edit/$1/$2/$3');
        $routes->post('(:num)/update', 'SectionController::update/$1/$2/$3');
        $routes->post('(:num)/delete', 'SectionController::delete/$1/$2/$3');
        $routes->post('(:num)/moveDown', 'SectionController::moveDown/$1/$2/$3');
        $routes->post('(:num)/moveUp', 'SectionController::moveUp/$1/$2/$3');

        // Questions per section

        $routes->get('(:num)/questions', 'QuestionnairController::manageSectionQuestions/$1/$2/$3');
        $routes->get('(:num)/questions/get-op/(:num)', 'QuestionnairController::getQuestionOptions/$1/$2/$3/$4');
        $routes->get('(:num)/questions/get-conditions/(:num)', 'QuestionnairController::getOption/$4');
        $routes->post('(:num)/questions/store', 'QuestionnairController::storeSectionQuestion/$1/$2/$3');
        $routes->get('(:num)/questions/get/(:num)', 'QuestionnairController::getQuestion/$1/$2/$3/$4');
        $routes->post('(:num)/questions/delete/(:num)', 'QuestionnairController::deleteSectionQuestion/$1/$2/$3/$4');
        $routes->post('(:num)/questions/(:num)/update', 'QuestionnairController::updateQuestion/$1/$2/$3/$4');
        // $routes->post('(:num)/questions/delete/(:num)', 'QuestionnairController::deleteSectionQuestion/$1/$2/$3/$4');
    });

    // === Option Management ===

    $routes->group('questions/(:num)/options', function ($routes) {
        $routes->get('/', 'QuestionnaireController::manageOptions/$1');
        $routes->post('store', 'QuestionnaireController::storeOption/$1');
        $routes->post('(:num)/update', 'QuestionnaireController::updateOption/$1');
        $routes->post('(:num)/delete', 'QuestionnaireController::deleteOption/$1');
    });

    // === Condition Management ===
    $routes->group('questions/(:num)/conditions', function ($routes) {
        $routes->get('/', 'QuestionnaireConditionController::index/$1');
        $routes->get('create', 'QuestionnaireConditionController::create/$1');
        $routes->post('store', 'QuestionnaireConditionController::store/$1');
        $routes->get('(:num)/edit', 'QuestionnaireConditionController::edit/$1/$2');
        $routes->post('(:num)/update', 'QuestionnaireConditionController::update/$1/$2');
        $routes->post('(:num)/delete', 'QuestionnaireConditionController::delete/$1/$2');
    });
});

// ===============================
// KAPRODI ROUTES
// ===============================
// $routes->group('kaprodi', ['filter' => 'kaprodiAuth'], function ($routes) {
//     $routes->get('dashboard', 'KaprodiController::dashboard');
//     $routes->get('supervisi', 'KaprodiController::supervisi');
//     $routes->get('questioner', 'KaprodiController::questioner');
//     $routes->get('akreditasi', 'KaprodiController::akreditasi');
//     $routes->get('ami', 'KaprodiController::ami');
//     $routes->get('profil', 'KaprodiController::profil');
// });

// --------------------
// ROUTES: Satuan Organisasi + Nested
// --------------------
$routes->group('satuanorganisasi', ['filter' => 'adminAuth'], ['namespace' => 'App\Controllers'], function ($routes) {

    // Satuan Organisasi - Main
    $routes->get('', 'SatuanOrganisasi::index');
    $routes->get('create', 'SatuanOrganisasi::create');
    $routes->post('store', 'SatuanOrganisasi::store');
    $routes->get('edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->post('delete/(:num)', 'SatuanOrganisasi::delete/$1');
    $routes->get('getProdi/(:num)', 'ProdiController::getProdi/$1');

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

//alumni surveyor
$routes->get('alumni/supervisi', 'AlumniController::supervisi');
/// ROUTES ALUMNI
$routes->group('alumni', ['filter' => 'alumniAuth'], static function ($routes) {
    // -------------------------------
    // Login & Logout
    // -------------------------------
    $routes->get('login', 'AlumniController::login');
    $routes->post('login', 'AlumniController::doLogin');
    $routes->get('logout', 'AlumniController::logout');

    // -------------------------------
    // Dashboard
    // -------------------------------
    $routes->get('dashboard', 'AlumniController::dashboard');
    $routes->get('surveyor/dashboard', 'AlumniController::dashboard/surveyor'); // alumni biasa

    // surveyor

    // -------------------------------
    // Profil Alumni Biasa
    // -------------------------------
    $routes->get('profil', 'AlumniController::profil');                  // index profil
    $routes->post('profil/update', 'AlumniController::updateProfil');
    $routes->post('profil/update-foto/(:num)', 'AlumniController::updateFoto/$1');

    // Tentang Pekerjaan
    $routes->get('profil/pekerjaan', 'AlumniController::pekerjaan');
    $routes->post('profil/pekerjaan/save', 'AlumniController::savePekerjaan');

    // Riwayat Pekerjaan
    $routes->get('profil/riwayat', 'AlumniController::riwayatPekerjaan');
    $routes->get('profil/riwayat/delete/(:num)', 'AlumniController::deleteRiwayat/$1');

    // -------------------------------
    // Profil Alumni Surveyor
    // -------------------------------
    $routes->group('surveyor', static function ($routes) {
        $routes->get('profil', 'AlumniController::profilSurveyor');
        $routes->post('profil/update', 'AlumniController::updateProfil');
        $routes->post('profil/update-foto/(:num)', 'AlumniController::updateFoto/$1');

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
    // Supervisi & Lihat Teman
    // -------------------------------
    $routes->get('lihat_teman', 'AlumniController::lihatTeman');
    // $routes->get('supervisi', 'AlumniController::supervisi');

    // -------------------------------
    // Questionnaires / Kuesioner Alumni Biasa
    // -------------------------------
    $routes->get('questionnaires', 'UserQuestionController::index');
    $routes->get('questionnaires/mulai/(:num)', 'UserQuestionController::mulai/$1');
    $routes->get('questionnaires/lanjutkan/(:num)', 'UserQuestionController::lanjutkan/$1');
    $routes->get('questioner/lihat/(:num)', 'UserQuestionController::lihat/$1');

    $routes->post('questionnaires/save-answer', 'UserQuestionController::saveAnswer');
});



$routes->get('email-test', 'EmailTest::index');






$routes->get('pengaturan-situs', 'PengaturanSitus::index');
$routes->post('pengaturan-situs/save', 'PengaturanSitus::save');
// $routes->get('alumni/login', 'Alumni::login');
// $routes->post('alumni/login', 'Alumni::doLogin');
// $routes->get('alumni/dashboard', 'Alumni::dashboard');
// $routes->get('alumni/logout', 'Alumni::logout');

// Pengaturan Situs
$routes->get('/pengaturan-situs', 'PengaturanSitus::index');

// ajax conditional_logic 

$routes->get('/admin/get-conditional-options', 'QuestionnairController::getConditionalOptions', ['as' => 'admin.questioner.getOptions']);
// app/Config/Routes.php
$routes->get('admin/questionnaire/pages/get-question-options', 'QuestionnairePageController::getQuestionOptions');

// Di Routes.php tambahkan:
$routes->get('admin/questionnaire/(:num)/questions/(:num)/options', 'QuestionController::getQuestionOptions/$1/$2');
$routes->get('admin/questionnaire/(:num)/pages/(:num)/sections/(:num)/questions-with-options', 'QuestionController::getQuestionsWithOptions/$1/$2/$3');

// ===============================
// Admin - Laporan
// ===============================
$routes->group('admin', ['filter' => 'adminAuth'], ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('laporan', 'AdminLaporan::index');                     // list laporan (max 7)
    $routes->get('laporan/create', 'AdminLaporan::create');             // form tambah laporan
    $routes->post('laporan/save', 'AdminLaporan::save');                // simpan banyak laporan
    $routes->get('laporan/edit/(:num)', 'AdminLaporan::edit/$1');       // form edit laporan
    $routes->post('laporan/update/(:num)', 'AdminLaporan::update/$1');  // update laporan
});

// ===============================
// Landing Page (Public)
// ===============================
$routes->get('laporan', 'AdminLaporan::showAll');              // default → tahun terbaru (2024)
$routes->get('laporan/(:num)', 'AdminLaporan::showAll/$1');    // filter laporan per tahun


// ===============================
// Admin - Respon
// ===============================
// $routes->group('admin/respon', static function ($routes) {
//     $routes->get('/', 'AdminRespon::index'); // Tampilkan daftar respon
// });

$routes->group('admin/respon', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('/', 'AdminRespon::index');
    $routes->get('export', 'AdminRespon::exportExcel');

    // Tambahan baru
    $routes->get('grafik', 'AdminRespon::grafik');           // untuk tombol "Grafik"
    $routes->get('detail/(:num)', 'AdminRespon::detail/$1'); // untuk tombol "Jawaban"
});



// Landing page tetap
// $routes->get('/respon', 'UserQuestionController::responseLanding');

// // Route untuk user/landing page
$routes->get('/respon', 'UserQuestionController::responseLanding');


// =======================
// ROUTES KAPRODI
// =======================
$routes->group('kaprodi', ['filter' => 'kaprodiAuth'], function ($routes) {
    $routes->get('dashboard', 'KaprodiController::dashboard');
    $routes->get('supervisi', 'KaprodiController::supervisi');

    // MENU QUESTIONER
    $routes->get('questioner', 'KaprodiController::questioner');          // daftar kuesioner
    $routes->get('questioner/create', 'KaprodiController::create');      // form tambah kuesioner
    $routes->post('questioner/store', 'KaprodiController::store');       // proses simpan
    $routes->get('pertanyaan/(:num)', 'KaprodiController::pertanyaan/$1');
    $routes->get('questioner/(:num)/download', 'KaprodiController::downloadPertanyaan/$1'); // download PDF
    $routes->post('questioner/save_flags', 'KaprodiController::saveFlags');


    // MENU AKREDITASI
    $routes->get('akreditasi', 'KaprodiController::akreditasi');
    $routes->get('akreditasi/detail/(:any)', 'KaprodiController::detailAkreditasi/$1');

    // MENU AMI
    $routes->get('ami', 'KaprodiController::ami');
    $routes->get('ami/detail/(:any)', 'KaprodiController::detailAmi/$1');


    // PROFIL KAPRODI
    $routes->get('profil', 'KaprodiController::profil');
    $routes->get('profil/edit', 'KaprodiController::editProfil');
    $routes->post('profil/update', 'KaprodiController::updateProfil');
});


$routes->group('api', function ($routes) {
    $routes->get('cities/province/(:num)', 'penggunaController::getCitiesByProvince/$1');
});
