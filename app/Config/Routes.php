<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ================= AUTH =================
$routes->get('/', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ================= DASHBOARD =================
$routes->get('dashboard', 'Dashboard::index');

// ================= TRANSAKSI =================
$routes->get('barang-masuk', 'Transaksi::masuk');
$routes->post('barang-masuk/simpan', 'Transaksi::simpanMasuk');

$routes->get('barang-keluar', 'Transaksi::keluar');
$routes->post('barang-keluar/simpan', 'Transaksi::simpanKeluar');

// ================= BARANG =================
$routes->get('barang', 'Barang::index');
$routes->get('barang/tambah', 'Barang::tambah');
$routes->post('barang/simpan', 'Barang::simpan');

$routes->get('barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('barang/update/(:num)', 'Barang::update/$1');
$routes->get('barang/hapus/(:num)', 'Barang::hapus/$1');

$routes->get('barang/pdf', 'Barang::exportPdf');
$routes->get('barang/search', 'Barang::search');

// ================= HISTORI =================
$routes->get('histori', 'Transaksi::histori');
$routes->get('histori/pdf', 'Transaksi::exportPdf');
$routes->get('histori/excel', 'Transaksi::exportExcel');

// ================= TELEGRAM =================
$routes->match(['get', 'post'], 'telegram/webhook', 'Telegram::webhook');

$routes->get('telegram/morning-report', 'Telegram::morningReport');
$routes->get('telegram/test', 'Telegram::test');
$routes->get('telegram/check-alert', 'Telegram::checkAlert');
$routes->get('telegram/set-button', 'Telegram::setMiniAppButton');

// ================= MINI APP =================
$routes->get('miniapp', 'Miniapp::index');
$routes->get('miniapp/barang', 'Miniapp::barang');
$routes->get('miniapp/transaksi', 'Miniapp::transaksi');
$routes->get('miniapp/histori', 'Miniapp::histori');

$routes->post('telegram/triggerPdf', 'Telegram::triggerPdf');
