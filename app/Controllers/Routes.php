<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
// $routes->get('/login', 'Login::index');
// $routes->post('/operator/auth', 'Operator::auth');
// $routes->post('/operator/register', 'Operator::register');
// $routes->get('/logout', 'Login::logout');

// Route Home
$routes->get('/home/(:any)', 'Home::index');

// Route Maintenance
$routes->get('/maintenance/(:any)', 'Maintenance::index');

//pendaftar perangkatan
$routes->get('/pendaftar/(:any)', 'Pendaftar::index');
$routes->post('/pendaftar/cetak', 'Pendaftar::exportPendaftar');
$routes->post('/pendaftar/proses', 'Pendaftar::proses');

//calon mahasiswa perangkatan
$routes->get('/cama/(:any)', 'Cama::index');
$routes->post('/cama/proses', 'Cama::proses');
$routes->post('/cama/cetak', 'Cama::exportCama');

//registrasi ulang perangkatan
$routes->get('/regulang/(:any)', 'Regulang::index');
$routes->post('/regulang/proses', 'Regulang::proses');
$routes->post('/regulang/cetak', 'Regulang::exportRegulang');

//krs mahasiswa
$routes->get('/krs/(:any)', 'Krs::index');
$routes->post('/krs/proses', 'Krs::proses');
$routes->post('/krs/cetak', 'Krs::exportKrs');

//khs mahasiswa
$routes->get('/khs/(:any)', 'Khs::index');
$routes->post('/khs/proses', 'Khs::proses');
$routes->post('/khs/cetak', 'Khs::exportKhs');

//ipk mahasiswa
$routes->get('/ipk/(:any)', 'Ipk::index');
$routes->post('/ipk/proses', 'Ipk::proses');
$routes->post('/ipk/cetak', 'Ipk::exportIpk');

//detail mahasiswa aktif
$routes->get('/detailMhs/(:any)', 'DetailMhs::index');
$routes->post('/detailMhs/proses', 'DetailMhs::proses');
$routes->post('/detailMhs/cetak', 'DetailMhs::exportDetailMhs');

//total mahasiswa aktif
$routes->get('/totalMhs/(:any)', 'TotalMhs::index');
$routes->post('/totalMhs/proses', 'TotalMhs::proses');
$routes->post('/totalMhs/cetak', 'TotalMhs::exportTotalMhs');

//feeder
$routes->get('/feeder/(:any)', 'Feeder::index');
$routes->post('/feeder/proses', 'Feeder::proses');
$routes->post('/feeder/cetak', 'Feeder::exportFeeder');

//matkul
$routes->get('/matkul/(:any)', 'Matkul::index');
$routes->post('/matkul/proses', 'Matkul::proses');
$routes->post('/matkul/cetak', 'Matkul::exportMatkul');

//penugasan dosen
$routes->get('/dosen/(:any)', 'Dosen::index');
$routes->post('/dosen/proses', 'Dosen::proses');
$routes->post('/dosen/cetak', 'Dosen::exportDosen');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
