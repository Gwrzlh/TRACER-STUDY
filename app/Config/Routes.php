    <?php

    use CodeIgniter\Router\RouteCollection;

    /**
     * @var RouteCollection $routes
     */

    // --------------------
    // ROUTES: Auth/Login
    // --------------------
    $routes->get('/', 'LandingPage\LandingPage::index'); // Default landing
    $routes->get('login', 'Auth\Auth::login');
    $routes->post('do-login', 'Auth\Auth::doLogin');
    $routes->get('logout', 'Auth\Auth::logout');

    // --------------------
    // ROUTES: Forgot Password
    // --------------------
    $routes->get('lupapassword', 'Auth\Auth::forgotPassword');
    $routes->post('lupapassword', 'Auth\Auth::sendResetLink');
    $routes->get('resetpassword/(:any)', 'Auth\Auth::resetPassword/$1');
    $routes->post('resetpassword', 'Auth\Auth::doResetPassword');

    // --------------------
    // ROUTES: Admin
    // --------------------
    $routes->group('admin', ['filter' => 'auth'], function ($routes) {

        // Dashboard
        $routes->get('dashboard', 'Admin\AdminController::dashboard');

        // Welcome Page
        $routes->get('welcome-page', 'Admin\AdminWelcomePage::index');
        $routes->post('welcome-page/update', 'Admin\AdminWelcomePage::update');

        // Laporan
        $routes->get('laporan', 'Admin\AdminLaporan::index');
        $routes->get('laporan/create', 'Admin\AdminLaporan::create');
        $routes->post('laporan/save', 'Admin\AdminLaporan::save');
        $routes->get('laporan/edit/(:num)', 'Admin\AdminLaporan::edit/$1');
        $routes->post('laporan/update/(:num)', 'Admin\AdminLaporan::update/$1');

        // Respon
        // $routes->get('respon', 'Admin\AdminRespon::index');

        // Email Template
        $routes->get('emailtemplate', 'Admin\AdminEmailTemplateController::index');
        $routes->post('emailtemplate/update/(:num)', 'Admin\AdminEmailTemplateController::update/$1');

        // Kontak (Admin)
        $routes->group('kontak', ['namespace' => 'App\Controllers\LandingPage'], function ($routes) {
            $routes->get('/', 'Kontak::index');
            $routes->get('search', 'Kontak::search');
            $routes->post('delete/(:num)', 'Kontak::delete/$1');
            $routes->post('store-multiple', 'Kontak::storeMultiple');
        });

        // Tipe Organisasi
        $routes->group('tipeorganisasi', ['namespace' => 'App\Controllers\Organisasi'], function ($routes) {
            $routes->get('/', 'TipeOrganisasiController::index');
            $routes->get('form', 'TipeOrganisasiController::create');
            $routes->post('insert', 'TipeOrganisasiController::store');
            $routes->get('edit/(:num)', 'TipeOrganisasiController::edit/$1');
            $routes->post('edit/update/(:num)', 'TipeOrganisasiController::update/$1');
            $routes->post('delete/(:num)', 'TipeOrganisasiController::delete/$1');
        });

        // Pengguna
        $routes->group('pengguna', ['namespace' => 'App\Controllers\User'], function ($routes) {
            $routes->get('/', 'PenggunaController::index');
            $routes->get('tambahPengguna', 'PenggunaController::create');
            $routes->post('tambahPengguna/post', 'PenggunaController::store');
            $routes->get('editPengguna/(:num)', 'PenggunaController::edit/$1');
            $routes->post('update/(:num)', 'PenggunaController::update/$1');
            $routes->delete('delete/(:num)', 'PenggunaController::delete/$1');

            // Import
            $routes->get('import', '\App\Controllers\Auth\ImportAccount::form', ['filter' => 'auth']);
            $routes->post('import', '\App\Controllers\Auth\ImportAccount::process', ['filter' => 'auth']);
        });
// --------------------
// ROUTES: Questionnaire
// --------------------
$routes->group('questionnaire', ['namespace' => 'App\Controllers\Questionnaire'], function ($routes) {

    // ==== Questionnaire CRUD ====
    $routes->get('/', 'QuestionnaireController::index');
    $routes->get('create', 'QuestionnaireController::create');
    $routes->post('store', 'QuestionnaireController::store');
    $routes->get('(:num)', 'QuestionnaireController::show/$1');
    $routes->get('(:num)/edit', 'QuestionnaireController::edit/$1');
    $routes->post('(:num)/update', 'QuestionnaireController::update/$1');
    $routes->get('(:num)/delete', 'QuestionnaireController::delete/$1');
    $routes->post('(:num)/toggle-status', 'QuestionnaireController::toggleStatus/$1');
    $routes->get('(:num)/preview', 'QuestionnaireController::preview/$1');

    // ==== Questionnaire Pages ====
    $routes->get('(:num)/pages', 'QuestionnairePageController::index/$1');
    $routes->get('(:num)/pages/create', 'QuestionnairePageController::create/$1'); 
    $routes->post('(:num)/pages/store', 'QuestionnairePageController::store/$1');   
    $routes->get('(:num)/pages/(:num)/edit', 'QuestionnairePageController::edit/$1/$2'); 
    $routes->post('(:num)/pages/(:num)/update', 'QuestionnairePageController::update/$1/$2'); 
    $routes->get('(:num)/pages/(:num)/delete', 'QuestionnairePageController::delete/$1/$2'); 

    // ==== Questionnaire Sections ====
    $routes->get('(:num)/pages/(:num)/sections', 'QuestionnaireSectionController::index/$1/$2');
    $routes->get('(:num)/pages/(:num)/sections/create', 'QuestionnaireSectionController::create/$1/$2');
    $routes->post('(:num)/pages/(:num)/sections/store', 'QuestionnaireSectionController::store/$1/$2');
    $routes->get('(:num)/pages/(:num)/sections/(:num)/edit', 'QuestionnaireSectionController::edit/$1/$2/$3');
    $routes->post('(:num)/pages/(:num)/sections/(:num)/update', 'QuestionnaireSectionController::update/$1/$2/$3');
    $routes->get('(:num)/pages/(:num)/sections/(:num)/delete', 'QuestionnaireSectionController::delete/$1/$2/$3');

    // ==== Section Questions ====
    $routes->get('(:num)/pages/(:num)/sections/(:num)/questions', 'QuestionnaireController::manageSectionQuestions/$1/$2/$3');
    $routes->get('(:num)/pages/(:num)/sections/(:num)/questions/create', 'QuestionnaireController::createSectionQuestion/$1/$2/$3');
    $routes->post('(:num)/pages/(:num)/sections/(:num)/questions/store', 'QuestionnaireController::storeSectionQuestion/$1/$2/$3');
    $routes->get('(:num)/pages/(:num)/sections/(:num)/questions/(:num)/edit', 'QuestionnaireController::editQuestion/$1/$2/$3/$4');
    $routes->post('(:num)/pages/(:num)/sections/(:num)/questions/(:num)/update', 'QuestionnaireController::updateQuestion/$1/$2/$3/$4');
    $routes->get('(:num)/pages/(:num)/sections/(:num)/questions/(:num)/delete', 'QuestionnaireController::deleteSectionQuestion/$1/$2/$3/$4');

});




        // Pengaturan Situs
        $routes->get('pengaturan-situs', 'LandingPage\PengaturanSitus::index');
        $routes->post('pengaturan-situs/save', 'LandingPage\PengaturanSitus::save');

        // Tentang
        $routes->get('tentang/edit', 'LandingPage\Tentang::edit');
        $routes->post('tentang/update', 'LandingPage\Tentang::update');
    });

 // --------------------
// ROUTES: Satuan Organisasi
// --------------------
$routes->group('satuanorganisasi', ['namespace' => 'App\Controllers\Organisasi', 'filter' => 'auth'], function ($routes) {
    $routes->get('/', 'SatuanOrganisasi::index');
    $routes->get('create', 'SatuanOrganisasi::create');
    $routes->post('store', 'SatuanOrganisasi::store');
    $routes->get('edit/(:num)', 'SatuanOrganisasi::edit/$1');
    $routes->post('update/(:num)', 'SatuanOrganisasi::update/$1');
    $routes->get('delete/(:num)', 'SatuanOrganisasi::delete/$1');

    // AJAX
    $routes->get('get-prodi/(:num)', 'SatuanOrganisasi::getProdi/$1');
});


    // --------------------
    // ROUTES: Kaprodi, Perusahaan, Atasan, Jabatan, Alumni
    // --------------------
    // (semua oke, biarkan)
    // --------------------
    // --------------------
    // ROUTES: Public LandingPage
    // --------------------
    $routes->get('home', 'LandingPage\LandingPage::index');
    $routes->get('tentang', 'LandingPage\Tentang::index');
    $routes->get('kontak', 'LandingPage\Kontak::landing');
    $routes->get('sop', 'LandingPage\Sop::index');
    $routes->get('event', 'LandingPage\Event::index');
    $routes->get('respon', 'User\UserQuestionController::responseLanding');



    // routes untuk laporan tracer study
    $routes->get('laporan', 'LandingPage\Laporan::index');        // default → tahun terbaru
    $routes->get('laporan/(:num)', 'LandingPage\Laporan::index/$1'); // laporan berdasarkan tahun


    // --------------------
    // ROUTES: API (AJAX)
    // --------------------
    $routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('cities/province/(:num)', 'User\PenggunaController::getCitiesByProvince/$1');
        $routes->get('admin/questionnaire/(:num)/questions/(:num)/options', 'Questionnaire\QuestionnaireController::getQuestionOptions/$1/$2');
        $routes->get('admin/questionnaire/(:num)/pages/(:num)/sections/(:num)/questions-with-options', 'Questionnaire\QuestionnaireController::getQuestionsWithOptions/$1/$2/$3');
    });
                                                                                                                    
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('profil', 'Admin\AdminController::profil');
   $routes->post('profil/update-foto/(:num)', 'Admin\AdminController::updateFoto/$1');
    $routes->get('profil/edit/(:num)', 'Admin\AdminController::editProfil/$1');
    $routes->post('profil/updateProfil/(:num)', 'Admin\AdminController::updateProfil/$1');
     $routes->get('log_activities', 'Admin\LogController::index');
});

    // Respon
    $routes->get('admin/respon', 'Admin\AdminRespon::index');
    $routes->get('admin/respon/grafik', 'Admin\AdminRespon::grafik');
    $routes->get('admin/respon/export', 'Admin\AdminRespon::exportExcel');
    // $routes->post('admin/respon/export', 'Admin\AdminRespon::exportExcel');

// --------------------
// ROUTES: Alumni
// --------------------
$routes->group('alumni', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User\AlumniController::dashboard');
    $routes->get('profil', 'User\AlumniController::profil');
    $routes->get('edit-profil', 'User\AlumniController::editProfil');
    $routes->get('notifikasi', 'User\AlumniController::notifikasi');
    $routes->get('lihat_teman', 'User\AlumniController::lihatTeman');
    $routes->get('profil/edit', 'User\AlumniController::editProfil');

    // 👉 arahkan ke QuestionnaireController
  // ✅ PAKAI UserQuestionController
$routes->get('questionnaires', 'User\UserQuestionController::index');
$routes->get('questionnaires/mulai/(:num)', 'User\UserQuestionController::mulai/$1');
$routes->get('questionnaires/lanjutkan/(:num)', 'User\UserQuestionController::lanjutkan/$1');
$routes->get('questioner/lihat/(:num)', 'User\UserQuestionController::lihat/$1');
$routes->post('questionnaires/save-answer', 'User\UserQuestionController::saveAnswer');


});


// --------------------
// ROUTES: Kaprodi
// --------------------
$routes->group('kaprodi', [
    'namespace' => 'App\Controllers\User',
    'filter'    => 'auth'
], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'KaprodiController::dashboard');

    // Profil
    $routes->get('profil', 'KaprodiController::profil');
    $routes->get('profil/edit', 'KaprodiController::editProfil');
    $routes->post('profil/update', 'KaprodiController::updateProfil');

    // Kuesioner
    $routes->get('questioner', 'KaprodiController::questioner');
    $routes->get('questioner/pertanyaan/(:num)', 'KaprodiController::pertanyaan/$1');
    $routes->get('questioner/download/(:num)', 'KaprodiController::downloadPertanyaan/$1');
    $routes->post('questioner/add-to-akreditasi', 'KaprodiController::addToAkreditasi');
    $routes->post('questioner/add-to-ami', 'KaprodiController::addToAmi');
    $routes->post('questioner/save-flags', 'KaprodiController::saveFlags');

    // Akreditasi
    $routes->get('akreditasi', 'KaprodiController::akreditasi');
    $routes->get('akreditasi/detail/(:any)', 'KaprodiController::detailAkreditasi/$1');

    // AMI
    $routes->get('ami', 'KaprodiController::ami');
    $routes->get('ami/detail/(:any)', 'KaprodiController::detailAmi/$1');

    // (tambahkan route lain jika nanti kamu aktifkan method create/store/pages dsb.)
});

// --------------------
// ROUTES: Atasan
// --------------------
$routes->group('atasan', [
    'namespace' => 'App\Controllers\User', // sesuai folder
    'filter'    => 'auth'
], function ($routes) {

    // Dashboard → AtasanController
    $routes->get('dashboard', 'AtasanController::dashboard');

    // Kuesioner → AtasanKuesionerController
    $routes->get('kuesioner', 'AtasanKuesionerController::index');

    // (tambahkan route lain sesuai kebutuhan)
});

