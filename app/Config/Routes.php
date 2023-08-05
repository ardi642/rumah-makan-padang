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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->get('api/menu/selectDatatable', 'API\Menu::selectDatatable');
$routes->get('api/menu/findByFilters', 'API\Menu::findByFilters');
$routes->post('api/menu', 'API\Menu::create');
$routes->delete('api/menu/(:any)', 'API\Menu::delete/$1');
$routes->put('api/menu/(:any)', 'API\Menu::update/$1');


$routes->get('api/karyawan/selectDatatable', 'API\Karyawan::selectDatatable');
$routes->post('api/karyawan', 'API\Karyawan::create');
$routes->delete('api/karyawan/(:any)', 'API\Karyawan::delete/$1');
$routes->put('api/karyawan/(:any)', 'API\Karyawan::update/$1');

$routes->get('api/pesanan/selectDatatable', 'API\Pesanan::selectDatatable');
$routes->get('api/dataPesanan/(:any)', 'API\Pesanan::dataPesanan/$1');
$routes->post('api/pesanan', 'API\Pesanan::create');
$routes->delete('api/pesanan/(:any)', 'API\Pesanan::delete/$1');
$routes->put('api/pesanan/(:any)', 'API\Pesanan::update/$1');

$routes->get('api/LabelPesanan/selectDatatable', 'API\LabelPesanan::selectDatatable');
$routes->post('api/LabelPesanan', 'API\LabelPesanan::create');
$routes->delete('api/LabelPesanan/(:any)', 'API\LabelPesanan::delete/$1');
$routes->put('api/LabelPesanan/(:any)', 'API\LabelPesanan::update/$1');


$routes->get('api/LabelPengeluaran/selectDatatable', 'API\LabelPengeluaran::selectDatatable');
$routes->post('api/LabelPengeluaran', 'API\LabelPengeluaran::create');
$routes->delete('api/LabelPengeluaran/(:any)', 'API\LabelPengeluaran::delete/$1');
$routes->put('api/LabelPengeluaran/(:any)', 'API\LabelPengeluaran::update/$1');

$routes->post('api/pengeluaran', 'API\Pengeluaran::create');
$routes->delete('api/pengeluaran/(:any)', 'API\pengeluaran::delete/$1');
$routes->put('api/pengeluaran/(:any)', 'API\pengeluaran::update/$1');

$routes->post('api/login', 'API\Auth::handleLogin');
$routes->get('api/laporan/selectDatatable', 'API\Laporan::selectDatatable');
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
