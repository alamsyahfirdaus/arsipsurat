<?php

namespace Config;

// Membuat instance baru dari kelas RouteCollection.
$routes = Services::routes();

// Memuat file routing sistem terlebih dahulu, sehingga aplikasi dan ENVIRONMENT
// dapat menimpanya jika diperlukan.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Konfigurasi Router
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
// $routes->set404Override();
$routes->set404Override(function() {
    // Mengatur halaman 404 yang akan ditampilkan ketika halaman tidak ditemukan.
    echo '<h1 style="text-align: center; font-weight: bold; font-family: Arial;"><a href="javascript:void(0)" onclick="history.back()">HALAMAN TIDAK DITEMUKAN!</a></h1>';
});

$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Definisi Route
 * --------------------------------------------------------------------
 */

// Menetapkan rute default untuk halaman awal aplikasi.
$routes->get('/', 'Auth::index');
// Menetapkan rute untuk halaman login.
$routes->add('login', 'Auth::login');
// Menetapkan rute untuk proses logout.
$routes->add('logout', 'Auth::logout');
// Menetapkan rute untuk halaman home.
$routes->add('home', 'Home::index');
// Menetapkan rute untuk halaman user.
$routes->add('user', 'Home::user');
// Menetapkan rute untuk menampilkan daftar pengguna.
$routes->post('list/user/(:any)', 'Home::list_pengguna/$1');
// Menetapkan rute untuk menyimpan data pengguna.
$routes->post('save/user', 'Home::save_pengguna');
// Menetapkan rute untuk menghapus pengguna.
$routes->post('delete/user/(:any)', 'Home::delete_pengguna/$1');

// Menetapkan rute untuk halaman jenis surat.
$routes->add('type', 'JenisSurat::index');
// Menetapkan rute untuk menampilkan daftar jenis surat.
$routes->post('list/type/(:any)', 'JenisSurat::list_jenis_surat/$1');
// Menetapkan rute untuk menyimpan data jenis surat.
$routes->post('save/type', 'JenisSurat::save_jenis_surat');
// Menetapkan rute untuk menghapus jenis surat.
$routes->post('delete/type/(:any)', 'JenisSurat::delete_jenis_surat/$1');

// Menetapkan rute untuk halaman surat.
$routes->add('letter', 'Surat::index');
// Menetapkan rute untuk menampilkan surat berdasarkan jenis surat.
$routes->get('letter/(:any)', 'Surat::jenis_surat/$1');
// Menetapkan rute untuk menampilkan daftar surat berdasarkan jenis surat.
$routes->post('list/letter/(:any)', 'Surat::list_surat/$1');
// Menetapkan rute untuk menampilkan daftar rekap surat berdasarkan jenis surat.
$routes->post('list/recap/(:any)', 'Surat::list_rekap_surat/$1');
// Menetapkan rute untuk menyimpan data surat.
$routes->post('save/letter', 'Surat::save_surat');
// Menetapkan rute untuk menghapus surat.
$routes->post('delete/letter/(:any)', 'Surat::delete_surat/$1');

/*
 * --------------------------------------------------------------------
 * Routing Tambahan
 * --------------------------------------------------------------------
 *
 * Akan sering terjadi kebutuhan untuk rute tambahan dan Anda
 * membutuhkan kemampuan untuk menggantikan setelan default dalam file ini. Routing berbasis lingkungan
 * adalah salah satu waktu seperti itu. Memerlukan() file rute tambahan di sini
 * untuk membuatnya terjadi.
 *
 * Anda akan memiliki akses ke objek $routes di dalam file tersebut tanpa
 * perlu me-reloadnya.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}