<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Dashboard::index');

$routes->get('/barang-masuk', 'Transaksi::masuk');
$routes->post('/barang-masuk/simpan', 'Transaksi::simpanMasuk');

$routes->get('/barang-keluar', 'Transaksi::keluar');
$routes->post('/barang-keluar/simpan', 'Transaksi::simpanKeluar');

$routes->get('/barang', 'Barang::index');
$routes->get('/barang/tambah', 'Barang::tambah');
$routes->post('/barang/simpan', 'Barang::simpan');

$routes->get('/barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('/barang/update/(:num)', 'Barang::update/$1');

$routes->get('/barang/hapus/(:num)', 'Barang::hapus/$1');

$routes->match(['get', 'post'], 'telegram/webhook', 'Telegram::webhook');

$routes->get('/histori', 'Transaksi::histori');

$routes->get('/histori/pdf', 'Transaksi::exportPdf');
$routes->get('/histori/excel', 'Transaksi::exportExcel');
