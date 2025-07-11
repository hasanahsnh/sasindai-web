<?php

use App\Http\Controllers\FCM\PushNotifikasiController;
use App\Http\Controllers\Firebase\BeritaController;
use App\Http\Controllers\Firebase\IndexController;
use App\Http\Controllers\Firebase\KatalogController;
use App\Http\Controllers\Firebase\LayananDanFiturAplikasiController;
use App\Http\Controllers\Firebase\LoginController;
use App\Http\Controllers\Firebase\MitraController;
use App\Http\Controllers\Firebase\Objek3DController;
use App\Http\Controllers\Firebase\PasarController;
use App\Http\Controllers\Firebase\PengirimanController;
use App\Http\Controllers\Firebase\PesananController;
use App\Http\Controllers\Firebase\ProdukController;
use App\Http\Controllers\Firebase\RolesController;
use App\Http\Controllers\Firebase\UserController;
use App\Http\Controllers\Rajaongkir\EkspedisiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Halaman landing
Route::get('/', [UserController::class, 'index']);

// Sign in
Route::get('/masuk', function () {
    return view('admin.pages.login');
})->name('login');

// Register Mitra
Route::get('/register-mitra', [MitraController::class, 'kirimVerifikasi'])->name('register-mitra');
Route::post('/signing-up', [MitraController::class, 'mitraRegister'])->name('signing-up');

// Kirim ulang email verifikasi
Route::post('/kirim-ulang-verifikasi', [MitraController::class, 'kirimUlangVerifikasi'])->name('kirim-ulang-verifikasi');

// Tampilkan halaman form reset password
Route::get('/reset-password', [LoginController::class, 'resetPassword'])->name('reset-password');

// Mengirim proses reset password
Route::post('password-reset', [LoginController::class, 'passwordReset'])->name('password-reset');

// Mulai proses login
Route::post('/login', [LoginController::class, 'login'])->name('masuk');

// Mulai proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('keluar');

// Tampilkan informasi motif (web)
Route::get('/motif/{id}', [KatalogController::class, 'show'])->name('motif-show');

// Fallback 404
Route::fallback(function () {
    return response()->view('404', [], 404);
});

// Gomaps
Route::get('/gomaps-script', function() {
    $apiKey = env('GOMAPS_KEY');
    return response()->json([
        'script_url' => "https://maps.gomaps.pro/maps/api/js?key={$apiKey}&libraries=geometry,places&callback=initMap"
    ]);
});
Route::get('/api-key', function () {
    return response()->json([
        'api_key' => env('GOMAPS_KEY')
    ]);
});
Route::get('/api-key-update', function () {
    return response()->json([
        'api_key' => env('GOMAPS_KEY')
    ]);
});

// form kepuasan
Route::get('/form-kepuasan', function () {
    return view('kuesioner-kepuasan-pengguna');
});

Route::get('/pembayaran-sukses', function () {
    return view('pembayaran-sukses');
});

// Middleware admin
Route::middleware(['admin'])->group(function () {
    // Beranda
    Route::get('/home',[IndexController::class, 'dashboard'])->name('dashboard');
    
    // Katalog Section
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/tambah-motif', [KatalogController::class, 'create'])->name('tambah-motif');
    Route::post('/simpan-motif', [KatalogController::class, 'store'])->name('simpan-motif');
    Route::get('/edit-motif/{id}', [KatalogController::class, 'edit'])->name('edit-motif');
    Route::put('/update-motif/{id}', [KatalogController::class, 'update'])->name('update-motif');
    Route::delete('/hapus-motif/{id}', [KatalogController::class, 'destroy'])->name('destroy-motif');
    Route::get('/download-data-katalog', [KatalogController::class, 'downloadDataKatalog'])->name('download-data-katalog');

    // Berita Section
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::get('/tambah-berita', [BeritaController::class, 'create'])->name('tambah-berita');
    Route::post('/simpan-berita', [BeritaController::class, 'store'])->name('simpan-berita');
    Route::get('/edit-berita/{id}', [BeritaController::class, 'edit'])->name('edit-berita');
    Route::put('/update-berita/{id}', [BeritaController::class, 'update'])->name('update-berita');
    Route::delete('/hapus-berita/{id}', [BeritaController::class, 'destroy'])->name('destroy-berita');
    Route::get('/download-data-berita', [BeritaController::class, 'downloadDataBerita'])->name('download-data-berita');

    // Pasar Section
    Route::get('/ka-pasar', [PasarController::class, 'index'])->name('ka-pasar');
    Route::post('/perbarui-status-toko', [PasarController::class, 'perbaruiStatus'])->name('simpan-toko');
    Route::get('/download-data-pasar', [PasarController::class, 'downloadDataPasar'])->name('download-data-pasar');

    // Roles Section
    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
    Route::post('/tambah-role', [RolesController::class, 'store'])->name('tambah-role');

    // Users Section
    Route::get('/users', [UserController::class, 'usersManagement'])->name('users-management');

    // FAQ Section
    Route::get('/faq', function () {
        return view('pages.faq');
    });

    // Generate QR Section
    Route::get('/qr-motif', function () {
        return view('pages.generated-qr');
    });

    // Beranda Aplikasi Section
    Route::get('/aplikasi', function () {
        return view('pages.beranda-apk');
    });

    // Layanan dan Fitur Aplikasi Section
    Route::get('/layanan-fitur-aplikasi', [LayananDanFiturAplikasiController::class, 'index'])->name('layanan.fitur.aplikasi');
    Route::post('/tambah-layanan', [LayananDanFiturAplikasiController::class, 'tambahLayanan'])->name('tambah.layanan');
    Route::post('/perbarui-status-layanan', [LayananDanFiturAplikasiController::class, 'perbaruiStatusLayanan'])->name('perbarui.status.layanan');

    // Notifikasi Section
    Route::get('/push-notifikasi', [PushNotifikasiController::class, 'index'])->name('push.notifikasi');
    Route::post('/kirim-notifikasi', [PushNotifikasiController::class, 'store'])->name('push.notifikasi.store');
    Route::delete('/hapus-notifikasi/{id}', [PushNotifikasiController::class, 'hapusNotifikasi'])->name('hapus.notifikasi');

});


// Middleware mitra
Route::middleware(['mitra'])->group(function () {
    // Halaman dashboard
    Route::get('/dashboard-mitra', [MitraController::class, 'index'])->name('dashboard-mitra');

    // Halaman daftar produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/tambah-produk', [ProdukController::class, 'tambahProduk'])->name('tambah.produk');
    Route::get('edit-produk/{id}', [ProdukController::class, 'edit'])->name('edit.produk');
    Route::put('/update-produk/{id}', [ProdukController::class, 'editProduk'])->name('update.produk');
    Route::delete('/hapus-produk/{id}', [ProdukController::class, 'hapusProduk'])->name('delete.produk');

    // Halaman informasi toko
    Route::get('data-toko', [MitraController::class, 'dataToko'])->name('data-toko');
    Route::post('/perbarui-data-toko', [MitraController::class, 'perbaruiDataToko'])->name('perbarui-data-toko');

    // Halaman pesanan
    Route::get('pesanan', [PesananController::class, 'index'])->name('pesanan');

    // Halaman semu pengiriman
    Route::get('pengiriman', [PengirimanController::class, 'index'])->name('semua.pengiriman');
    Route::post('/input-resi', [PengirimanController::class, 'createPengiriman'])->name('input.resi');

    Route::get('/print-rincian-pesanan/{order_id}', [PesananController::class, 'printRincianPesanan'])->name('print.rincian.pesanan');
    
});

// Middleware admin atau mitra untuk input model 3d
Route::middleware(['admin_or_mitra'])->group(function() {
    // Input objek 3d
    Route::get('/objek-3d', [Objek3DController::class, 'index'])->name('objek.3d');
    Route::get('/tambah-objek-3d', [Objek3DController::class, 'create'])->name('input.objek.3d');
    Route::post('/simpan-objek-3d', [Objek3DController::class, 'store'])->name('simpan.objek.3d');
    Route::put('/update-model/{id}', [Objek3DController::class, 'update'])->name('update.model');
    Route::delete('delete-model/{id}', [Objek3DController::class, 'deleteModel'])->name('delete.model');
});