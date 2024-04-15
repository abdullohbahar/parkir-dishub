<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\MasterJenisPengajuanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pemohon\DashboardPemohonController;
use App\Http\Controllers\Pemohon\Pengajuan\InputDataPermohonanController;
use App\Http\Controllers\Pemohon\Pengajuan\PilihJenisPengajuanController;
use App\Http\Controllers\Pemohon\PengajuanPermohonanController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('auth', [AuthController::class, 'authenticate'])->name('authenticate');

// keycloak
Route::get('/login', [AuthController::class, 'redirectToKeycloak'])->name('login.keycloak');
Route::get('/callback', [AuthController::class, 'handleKeycloakCallback'])->name('keycloak.callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('keycloak.logout');

// admin
Route::prefix('admin')->middleware('check.profile')->group(function () {
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('parkir', MasterJenisPengajuanController::class)->only('index');
    Route::resource('user', UserController::class)->only('index', 'create', 'store');
});


Route::prefix('pemohon')->middleware('check.profile')->group(function () {
    Route::get('dashboard', [DashboardPemohonController::class, 'index'])->name('pemohon.dashboard');

    Route::prefix('permohonan')->group(function () {
        Route::get('/', [PengajuanPermohonanController::class, 'index'])->name('pemohon.pengajuan.permohonan');

        Route::prefix('pilih-jenis-pengajuan')->group(function () {
            Route::get('/', [PilihJenisPengajuanController::class, 'index'])->name('pemohon.pilih.jenis.pengajuan');
            Route::post('/store', [PilihJenisPengajuanController::class, 'store'])->name('pemohon.store.jenis.pengajuan');

            Route::get('/get-tipe-pengajuan/{jenisPengajuanID}', [PilihJenisPengajuanController::class, 'getTipePengajuan'])->name('pemohon.get.tipe.pengajuan');
        });

        Route::prefix('input-data-permohonan')->group(function () {
            Route::get('/{pengajuanID}', [InputDataPermohonanController::class, 'index'])->name('pemohon.input.data.permohonan');
            Route::post('store/{pengajuanID}', [InputDataPermohonanController::class, 'store'])->name('pemohon.store.data.permohonan');
        });
    });
});

Route::resource('profile', ProfileController::class)->only('index', 'edit', 'update');
