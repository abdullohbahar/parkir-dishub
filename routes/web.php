<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DetailJadwalTinjauanLapangan;
use App\Http\Controllers\Admin\PengajuanAdminController;
use App\Http\Controllers\Admin\MasterJenisPengajuanController;
use App\Http\Controllers\Admin\RevisiDokumenPermohonan;
use App\Http\Controllers\Admin\SetujuiDokumenPermohonan;
use App\Http\Controllers\Admin\SuratKeputusanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiDokumenController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kabid\DashboardKabidController;
use App\Http\Controllers\Kadis\DashboardKadisController;
use App\Http\Controllers\Kasi\DashboardKasiController;
use App\Http\Controllers\Pemohon\DashboardPemohonController;
use App\Http\Controllers\Pemohon\Pengajuan\InputDataPermohonanController;
use App\Http\Controllers\Pemohon\Pengajuan\PilihJenisPengajuanController;
use App\Http\Controllers\Pemohon\Pengajuan\SuratKeputusanController as PengajuanSuratKeputusanController;
use App\Http\Controllers\Pemohon\Pengajuan\SuratKesanggupanController;
use App\Http\Controllers\Pemohon\Pengajuan\TinjauanLapanganController;
use App\Http\Controllers\Pemohon\Pengajuan\UploadDokumenPengajuanController;
use App\Http\Controllers\Pemohon\PengajuanPermohonanController;
use App\Http\Controllers\PreviewSuratKeputusanController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Template\TemplateJadwalTinjauanLapangan;
use App\Models\SuratKeputusan;
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
Route::prefix('admin')->middleware(['admin', 'check.profile'])->group(function () {
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('parkir', MasterJenisPengajuanController::class)->only('index');
    Route::resource('user', UserController::class);


    Route::prefix('permohonan')->group(function () {
        Route::get('/', [PengajuanAdminController::class, 'index'])->name('admin.data.permohonan');

        Route::get('/detail/{pengajuanID}', [PengajuanPermohonanController::class, 'detail'])->name('admin.detail.permohonan');
        Route::get('verifikasi-dokumen/{pengajuanID}', [PengajuanAdminController::class, 'verifikasiDokumen'])->name('admin.verifikasi.dokumen');
        Route::post('revisi-dokumen', [VerifikasiDokumenController::class, 'revisi'])->name('admin.revisi.dokumen');
        Route::get('setujui-dokumen/{dokumenID}', [VerifikasiDokumenController::class, 'setujui'])->name('admin.setujui.dokumen');
        Route::put('tolak-dokumen/{pengajuanID}', [VerifikasiDokumenController::class, 'tolak'])->name('admin.tolak.dokumen');

        // jadwal tinjauan lapangan admin
        Route::post('go-to-jadwal-tinjauan-lapangan/{pengajuanID}', [PengajuanAdminController::class, 'goToJadwalTinjauanLapangan'])->name('admin.go.to.jadwal.tinjauan.lapangan');
        Route::get('input-jadwal-tinjauan-lapangan/{pengajuanID}', [PengajuanAdminController::class, 'JadwalTinjauanLapangan'])->name('admin.jadwal.tinjauan.lapangan');
        Route::post('store-jadwal-tinjauan-lapangan', [PengajuanAdminController::class, 'storeJadwalTinjauanLapangan'])->name('admin.store.jadwal.tinjauan.lapangan');
        Route::get('detail-jadwal-tinjauan-lapangan/{jadwalID}', DetailJadwalTinjauanLapangan::class)->name('admin.detail.jadwal.tinjauan.lapangan');
        Route::get('tinjauan-lapangan/{pengajuanID}', [PengajuanAdminController::class, 'tinjauanLapangan'])->name('admin.tinjauan.lapangan');
        Route::put('ubah-tinjauan-lapangan/{tinjauanID}', [PengajuanAdminController::class, 'ubahTinjauanLapangan'])->name('admin.ubah.tinjauan.lapangan');
        Route::post('tinjauan-lapangan-selesai/{jadwalID}', [PengajuanAdminController::class, 'telahMelakukanTinjauan'])->name('admin.tinjauan.lapangan.selesai');
        Route::get('menunggu-surat-kesanggupan/{pengajuanID}', [PengajuanAdminController::class, 'menungguSuratKesanggupan'])->name('admin.menunggu.surat.kesanggupan');
        Route::get('verifikasi-surat-kesanggupan/{pengajuanID}', [PengajuanAdminController::class, 'verifikasiSuratKesanggupan'])->name('admin.verifikasi.surat.kesanggupan');
        Route::post('approve-surat-kesanggupan/{pengajuanID}', [PengajuanAdminController::class, 'approveSuratKesanggupan'])->name('admin.approve.surat.kesanggupan');
        Route::get('membuat-surat-keputusan/{pengajuanID}', [PengajuanAdminController::class, 'suratKeputusan'])->name('admin.surat.keputusan');
        Route::post('kirim-surat-keputusan-ke-kasi/{pengajuanID}', [PengajuanAdminController::class, 'kirimSuratKeputusanKeKasi'])->name('admin.kirim.surat.keputusan.kekasi');
        Route::post('kirim-surat-keputusan-ke-bantara/{pengajuanID}', [PengajuanAdminController::class, 'kirimSuratKeputusanKeBantara'])->name('admin.kirim.surat.keputusan.kebantara');
        Route::get('get-signed-document-from-bantara/{pengajuanID}', [PengajuanAdminController::class, 'getSignedDocumentFromBantara'])->name('admin.get.signed.document.from.bantara');
        Route::get('menunggu-approve-surat-keputusan/{pengajuanID}', [PengajuanAdminController::class, 'menungguApproveSuratKeputusan'])->name('admin.menunggu.approve.surat.keputusan');
    });
});


Route::prefix('pemohon')->middleware(['pemohon', 'check.profile'])->group(function () {
    Route::get('dashboard', [DashboardPemohonController::class, 'index'])->name('pemohon.dashboard');
    Route::get('tes-email', [DashboardPemohonController::class, 'tesEmail']);

    Route::prefix('permohonan')->group(function () {
        Route::get('/', [PengajuanPermohonanController::class, 'index'])->name('pemohon.pengajuan.permohonan');
        Route::get('/detail/{pengajuanID}', [PengajuanPermohonanController::class, 'detail'])->name('pemohon.detail.permohonan');
        Route::get('/revisi/{pengajuanID}', [PengajuanPermohonanController::class, 'revisiPage'])->name('pemohon.revisi.permohonan');
        Route::put('/revisi/update', [PengajuanPermohonanController::class, 'revisiAction'])->name('pemohon.revisi.dokumen');

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

        Route::prefix('jadwal-tinjauan-lapangan')->group(function () {
            Route::get('/{pengajuanID}', [TinjauanLapanganController::class, 'index'])->name('pemohon.jadwal.tinjauan.lapangan');
        });

        Route::prefix('surat-kesanggupan')->group(function () {
            Route::get('{pengajuanID}', [SuratKesanggupanController::class, 'index'])->name('pemohon.create.surat.kesanggupan');
            Route::get('stream-template-surat-kesanggupan/{pengajuanID}', [SuratKesanggupanController::class, 'templateSuratKesanggupan'])->name('stream.template.surat.kesanggupan');
            Route::post('upload/{pengajuanID}', [SuratKesanggupanController::class, 'upload'])->name('pemohon.upload.surat.kesanggupan');
            Route::get('menunggu-verifikasi-surat-kesanggupan/{pengajuanID}', [SuratKesanggupanController::class, 'menungguVerifikasi'])->name('pemohon.menunggu.verifikasi.surat.kesanggupan');
            Route::get('menunggu-verifikasi-surat-keputusan/{pengajuanID}', [PengajuanSuratKeputusanController::class, 'menungguVerifikasi'])->name('pemohon.menunggu.verifikasi.surat.keputusan');
        });

        Route::get('selesai/{pengajuanID}', [PengajuanPermohonanController::class, 'pengajuanSelesai'])->name('pemohon.pengajuan.selesai');
    });
});

Route::prefix('kasi')->middleware(['kasi', 'check.profile'])->group(function () {
    Route::get('dashboard', [DashboardKasiController::class, 'index'])->name('kasi.dashboard');
    Route::get('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKasiController::class, 'setujui'])->name('kasi.verifikasi.surat.keputusan');
    Route::post('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKasiController::class, 'kirimSuratKeputusanKeKabid'])->name('kasi.kirim.surat.keputusan.kekabid');
});

Route::prefix('kabid')->middleware(['kabid', 'check.profile'])->group(function () {
    Route::get('dashboard', [DashboardKabidController::class, 'index'])->name('kabid.dashboard');
    Route::get('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKabidController::class, 'setujui'])->name('kabid.verifikasi.surat.keputusan');
    Route::post('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKabidController::class, 'kirimSuratKeputusanKeKadis'])->name('kabid.kirim.surat.keputusan.kekadis');
});

Route::prefix('kadis')->middleware(['kadis', 'check.profile'])->group(function () {
    Route::get('dashboard', [DashboardKadisController::class, 'index'])->name('kadis.dashboard');
    Route::get('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKadisController::class, 'setujui'])->name('kadis.verifikasi.surat.keputusan');
    Route::post('verifikasi-surat-keputusan/{pengajuanID}', [DashboardKadisController::class, 'kirimSuratKeputusanKeKadis'])->name('kadis.kirim.surat.keputusan.kekadis');
});

Route::get('profile/{id}', [ProfileController::class, 'index'])->name('profile.index');
Route::resource('profile', ProfileController::class)->only('edit', 'update', 'show');
Route::get('download-pemberitahuan-jadwal-tinjauan-lapangan/{pengajuanID}', TemplateJadwalTinjauanLapangan::class)->name('download.pemberitahuan.jadwal.tinjauan');
Route::get('surat-keputusan/{pengajuanID}', SuratKeputusanController::class)->name('surat.keputusan');
Route::get('preview-surat-keputusan/{pengajuanID}', PreviewSuratKeputusanController::class)->name('preview.surat.keputusan');

// Callback BANTARA TTE
Route::post('/callback/bantara', [PengajuanAdminController::class, 'callbackBantara'])->name('callback.bantara');
