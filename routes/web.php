<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\PengajuanAdminController;
use App\Http\Controllers\Admin\MasterJenisPengajuanController;
use App\Http\Controllers\Admin\RevisiDokumenPermohonan;
use App\Http\Controllers\Admin\SetujuiDokumenPermohonan;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiDokumenController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pemohon\DashboardPemohonController;
use App\Http\Controllers\Pemohon\Pengajuan\InputDataPermohonanController;
use App\Http\Controllers\Pemohon\Pengajuan\PilihJenisPengajuanController;
use App\Http\Controllers\Pemohon\Pengajuan\UploadDokumenPengajuanController;
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


    Route::prefix('permohonan')->group(function () {
        Route::get('/', [PengajuanAdminController::class, 'index'])->name('admin.data.permohonan');

        Route::get('verifikasi-dokumen/{pengajuanID}', [PengajuanAdminController::class, 'verifikasiDokumen'])->name('admin.verifikasi.dokumen');
        Route::post('revisi-dokumen', [VerifikasiDokumenController::class, 'revisi'])->name('admin.revisi.dokumen');
        Route::get('setujui-dokumen/{dokumenID}', [VerifikasiDokumenController::class, 'setujui'])->name('admin.setujui.dokumen');
        Route::put('tolak-dokumen/{pengajuanID}', [VerifikasiDokumenController::class, 'tolak'])->name('admin.tolak.dokumen');
        Route::post('go-to-jadwal-tinjauan-lapangan/{pengajuanID}', [PengajuanAdminController::class, 'goToJadwalTinjauanLapangan'])->name('admin.go.to.jadwal.tinjauan.lapangan');

        Route::get('input-jadwal-tinjauan-lapangan/{pengajuanID}', [PengajuanAdminController::class, 'JadwalTinjauanLapangan'])->name('admin.jadwal.tinjauan.lapangan');
    });
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

        Route::prefix('upload-dokumen-permohonan')->group(function () {
            Route::get('/{pengajuanID}', [UploadDokumenPengajuanController::class, 'index'])->name('pemohon.upload.dokumen.pengajuan');
            Route::post('store/{pengajuanID}', [UploadDokumenPengajuanController::class, 'uploadDokumen'])->name('pemohon.store.dokumen.pengajuan');
            Route::post('next/{pengajuanID}', [UploadDokumenPengajuanController::class, 'next'])->name('pemohon.next.dokumen.pengajuan');
            Route::get('menunggu-verifikasi-admin/{pengajuanID}', [UploadDokumenPengajuanController::class, 'waitVerification'])->name('pemohon.wait.verification.dokumen.pengajuan');

            Route::get('template-surat-permohonan/{pengajuanID}', [UploadDokumenPengajuanController::class, 'streamSuratPermohonan'])->name('pemohon.template.surat.permohonan');
            Route::get('download-surat-permohonan/{pengajuanID}', [UploadDokumenPengajuanController::class, 'downloadSuratPermohonan'])->name('pemohon.download.surat.permohonan');
        });
    });
});

Route::resource('profile', ProfileController::class)->only('index', 'edit', 'update');
