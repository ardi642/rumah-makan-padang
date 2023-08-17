<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Logout::index');

$routes->get('/menu', 'Menu::index');
$routes->get('/menu/tambah', 'Menu::tambah');
$routes->get('/menu/edit/(:any)', 'Menu::edit/$1');

$routes->get('/karyawan', 'Karyawan::index');
$routes->get('/karyawan/tambah', 'Karyawan::tambah');
$routes->get('/karyawan/edit/(:any)', 'Karyawan::edit/$1');

$routes->get('/pesanan', 'Pesanan::index');
$routes->get('/pesanan/detail/(:any)', 'Pesanan::detail/$1');
$routes->get('/pesanan/tambah', 'Pesanan::tambah');
$routes->get('/pesanan/edit/(:any)', 'Pesanan::edit/$1');

$routes->get('/LabelPesanan', 'LabelPesanan::index');
$routes->get('/LabelPesanan/tambah', 'LabelPesanan::tambah');
$routes->get('/LabelPesanan/edit/(:any)', 'LabelPesanan::edit/$1');

$routes->get('/pengeluaran', 'Pengeluaran::index');
$routes->get('/pengeluaran/tambah', 'Pengeluaran::tambah');
$routes->get('/pengeluaran/edit/(:any)', 'Pengeluaran::edit/$1');

$routes->get('/LabelPengeluaran', 'LabelPengeluaran::index');
$routes->get('/LabelPengeluaran/tambah', 'LabelPengeluaran::tambah');
$routes->get('/LabelPengeluaran/edit/(:any)', 'LabelPengeluaran::edit/$1');

$routes->get('/laporan', 'Laporan::index');

$routes->get('api/menu/selectDatatable', 'API\Menu::selectDatatable');
$routes->get('api/menu/find/(:any)', 'API\Menu::find/$1');
$routes->get('api/menu/findByFilters', 'API\Menu::findByFilters');
$routes->post('api/menu', 'API\Menu::create');
$routes->delete('api/menu/(:any)', 'API\Menu::delete/$1');
$routes->put('api/menu/(:any)', 'API\Menu::update/$1');

$routes->get('api/karyawan/selectDatatable', 'API\Karyawan::selectDatatable');
$routes->get('api/karyawan/find/(:any)', 'API\Karyawan::find/$1');
$routes->post('api/karyawan', 'API\Karyawan::create');
$routes->delete('api/karyawan/(:any)', 'API\Karyawan::delete/$1');
$routes->put('api/karyawan/(:any)', 'API\Karyawan::update/$1');

$routes->get('api/pesanan/selectDatatable', 'API\Pesanan::selectDatatable');
$routes->get('api/dataPesanan/(:any)', 'API\Pesanan::dataPesanan/$1');
$routes->post('api/pesanan', 'API\Pesanan::create');
$routes->delete('api/pesanan/(:any)', 'API\Pesanan::delete/$1');
$routes->put('api/pesanan/(:any)', 'API\Pesanan::update/$1');

$routes->get('api/LabelPesanan/selectDatatable', 'API\LabelPesanan::selectDatatable');
$routes->get('api/LabelPesanan/findByFilters', 'API\LabelPesanan::findByFilters');
$routes->post('api/LabelPesanan', 'API\LabelPesanan::create');
$routes->delete('api/LabelPesanan/(:any)', 'API\LabelPesanan::delete/$1');
$routes->put('api/LabelPesanan/(:any)', 'API\LabelPesanan::update/$1');

$routes->get('api/LabelPengeluaran/find/(:any)', 'API\LabelPengeluaran::find/$1');
$routes->get('api/LabelPengeluaran/selectDatatable', 'API\LabelPengeluaran::selectDatatable');
$routes->get('api/LabelPengeluaran/selectAll', 'API\LabelPengeluaran::selectAll');
$routes->get('api/LabelPengeluaran/findByFilters', 'API\LabelPengeluaran::findByFilters');
$routes->post('api/LabelPengeluaran', 'API\LabelPengeluaran::create');
$routes->delete('api/LabelPengeluaran/(:any)', 'API\LabelPengeluaran::delete/$1');
$routes->put('api/LabelPengeluaran/(:any)', 'API\LabelPengeluaran::update/$1');

$routes->get('api/pengeluaran/selectDatatable', 'API\Pengeluaran::selectDatatable');
$routes->post('api/pengeluaran', 'API\Pengeluaran::create');
$routes->delete('api/pengeluaran/(:any)', 'API\Pengeluaran::delete/$1');
$routes->put('api/pengeluaran/(:any)', 'API\Pengeluaran::update/$1');

$routes->post('api/login', 'API\Auth::handleLogin');
$routes->get('api/laporan/selectDatatable', 'API\Laporan::selectDatatable');

$routes->get('api/home/transaction', 'API\Home::getTransaction');
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
