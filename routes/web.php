<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;

// Halaman login (GET)
Route::get('/login', function () {
    return view('login');   // nama view: resources/views/login.blade.php
})->name('login');

// Proses login (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Halaman default root diarahkan ke login (atau welcome, pilih salah satu)
Route::get('/', function () {
    return redirect()->route('login');   // atau return view('welcome');
});

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

// routes/web.php
use App\Http\Controllers\GuruController;

Route::middleware(['auth'])->group(function () {
    Route::get('/guru', [GuruController::class, 'dashboard'])->name('guru.dashboard');

    Route::get('/guru/siswa', [GuruController::class, 'indexSiswa'])->name('guru.siswa.index');
    Route::get('/guru/siswa/create', [GuruController::class, 'createSiswa'])->name('guru.siswa.create');
    Route::post('/guru/siswa', [GuruController::class, 'storeSiswa'])->name('guru.siswa.store');

    Route::get('/guru/presensi', [GuruController::class, 'indexPresensi'])->name('guru.presensi.index');
    Route::get('/guru/presensi/create', [GuruController::class, 'createPresensi'])->name('guru.presensi.create');
    Route::post('/guru/presensi', [GuruController::class, 'storePresensi'])->name('guru.presensi.store');
});

use App\Http\Controllers\KebersihanController;

Route::middleware(['auth'])->group(function () {
    // ... route guru ...

    Route::get('/kebersihan', [KebersihanController::class, 'dashboard'])->name('kebersihan.dashboard');

    Route::get('/kebersihan/presensi', [KebersihanController::class, 'indexPresensi'])->name('kebersihan.presensi.index');
    Route::get('/kebersihan/presensi/create', [KebersihanController::class, 'createPresensi'])->name('kebersihan.presensi.create');
    Route::post('/kebersihan/presensi', [KebersihanController::class, 'storePresensi'])->name('kebersihan.presensi.store');
});

use App\Http\Controllers\TataUsahaController;

Route::middleware('auth')->prefix('tatausaha')->name('tatausaha.')->group(function () {
    Route::get('/', [TataUsahaController::class, 'dashboard'])->name('dashboard');

    // ...

    // Penggajian CRUD
    Route::get('gaji', [TataUsahaController::class, 'indexGaji'])->name('gaji.index');
    Route::get('gaji/create', [TataUsahaController::class, 'createGaji'])->name('gaji.create');
    Route::post('gaji', [TataUsahaController::class, 'storeGaji'])->name('gaji.store');
    Route::get('gaji/{gaji}/edit', [TataUsahaController::class, 'editGaji'])->name('gaji.edit');
    Route::put('gaji/{gaji}', [TataUsahaController::class, 'updateGaji'])->name('gaji.update');
    Route::delete('gaji/{gaji}', [TataUsahaController::class, 'destroyGaji'])->name('gaji.destroy');


    Route::get('spp', [TataUsahaController::class, 'indexSpp'])->name('spp.index');
    Route::get('spp/create', [TataUsahaController::class, 'createSpp'])->name('spp.create');
    Route::post('spp', [TataUsahaController::class, 'storeSpp'])->name('spp.store');
    Route::get('spp/{spp}/edit', [TataUsahaController::class, 'editSpp'])->name('spp.edit');
    Route::put('spp/{spp}', [TataUsahaController::class, 'updateSpp'])->name('spp.update');
    Route::delete('spp/{spp}', [TataUsahaController::class, 'destroySpp'])->name('spp.destroy');

    // Pendaftaran
    Route::get('pendaftaran', [TataUsahaController::class, 'indexPendaftaran'])->name('pendaftaran.index');
    Route::get('pendaftaran/create', [TataUsahaController::class, 'createPendaftaran'])->name('pendaftaran.create');
    Route::post('pendaftaran', [TataUsahaController::class, 'storePendaftaran'])->name('pendaftaran.store');
    Route::get('pendaftaran/{pendaftaran}/edit', [TataUsahaController::class, 'editPendaftaran'])->name('pendaftaran.edit');
    Route::put('pendaftaran/{pendaftaran}', [TataUsahaController::class, 'updatePendaftaran'])->name('pendaftaran.update');
    Route::delete('pendaftaran/{pendaftaran}', [TataUsahaController::class, 'destroyPendaftaran'])->name('pendaftaran.destroy');

    // Data Pegawai
    Route::get('pegawai', [TataUsahaController::class, 'indexPegawai'])->name('pegawai.index');
    Route::get('pegawai/create', [TataUsahaController::class, 'createPegawai'])->name('pegawai.create');
    Route::post('pegawai', [TataUsahaController::class, 'storePegawai'])->name('pegawai.store');
    Route::get('pegawai/{pegawai}/edit', [TataUsahaController::class, 'editPegawai'])->name('pegawai.edit');
    Route::put('pegawai/{pegawai}', [TataUsahaController::class, 'updatePegawai'])->name('pegawai.update');
    Route::delete('pegawai/{pegawai}', [TataUsahaController::class, 'destroyPegawai'])->name('pegawai.destroy');

    // Data Siswa
    Route::get('siswa', [TataUsahaController::class, 'indexSiswa'])->name('siswa.index');
    Route::get('siswa/create', [TataUsahaController::class, 'createSiswa'])->name('siswa.create');
    Route::post('siswa', [TataUsahaController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('siswa/{siswa}/edit', [TataUsahaController::class, 'editSiswa'])->name('siswa.edit');
    Route::put('siswa/{siswa}', [TataUsahaController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('siswa/{siswa}', [TataUsahaController::class, 'destroySiswa'])->name('siswa.destroy');

    // Chart of Account
    Route::get('coa', [TataUsahaController::class, 'indexCoa'])->name('coa.index');
    Route::get('coa/create', [TataUsahaController::class, 'createCoa'])->name('coa.create');
    Route::post('coa', [TataUsahaController::class, 'storeCoa'])->name('coa.store');
    Route::get('coa/{coa}/edit', [TataUsahaController::class, 'editCoa'])->name('coa.edit');
    Route::put('coa/{coa}', [TataUsahaController::class, 'updateCoa'])->name('coa.update');
    Route::delete('coa/{coa}', [TataUsahaController::class, 'destroyCoa'])->name('coa.destroy');
    });

use App\Http\Controllers\LaporanController;

Route::middleware(['auth'])->prefix('tatausaha')->name('tatausaha.')->group(function () {
    // ...
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
        Route::post('/laporan/{bulan}/{tahun}/ask',
        [\App\Http\Controllers\LaporanController::class, 'ask']
    )->name('laporan.ask');
});

Route::middleware(['auth'])->prefix('tatausaha')->name('tatausaha.')->group(function () {
    // ...
    Route::get('/presensi', [TataUsahaController::class, 'indexPresensi'])->name('presensi.index');
    Route::get('/presensi/create', [TataUsahaController::class, 'createPresensi'])->name('presensi.create');
    Route::post('/presensi', [TataUsahaController::class, 'storePresensi'])->name('presensi.store');
    Route::get('/presensi/{presensi}/edit', [TataUsahaController::class, 'editPresensi'])->name('presensi.edit');
    Route::put('/presensi/{presensi}', [TataUsahaController::class, 'updatePresensi'])->name('presensi.update');
    Route::delete('/presensi/{presensi}', [TataUsahaController::class, 'destroyPresensi'])->name('presensi.destroy');
});




