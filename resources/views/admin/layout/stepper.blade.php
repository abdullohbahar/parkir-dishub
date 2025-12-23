<div class="card mb-5">
    <div class="card-header">
        <div class="stepper-wrapper">
            <div class="stepper-item {{ Route::is('admin.verifikasi.dokumen') ? 'active' : 'completed' }}">
                <div class="step-counter">1</div>
                <div class="step-name text-center">Verifikasi Dokumen</div>
            </div>
            <div class="stepper-item {{ Route::is('admin.jadwal.tinjauan.lapangan') ? 'active' : 'completed' }}">
                <div class="step-counter">2</div>
                <div class="step-name text-center">Buat Jadwal Tinjauan Lapangan</div>
            </div>
            <div
                class="stepper-item {{ Route::is('pemohon.upload.dokumen.pengajuan') || Route::is('pemohon.wait.verification.dokumen.pengajuan') || Route::is('admin.tinjauan.lapangan') ? 'active' : 'completed' }}">
                <div class="step-counter">3</div>
                <div class="step-name text-center">Tinjauan Lapangan</div>
            </div>
            <div
                class="stepper-item {{ Route::is('admin.menunggu.surat.kesanggupan') || Route::is('admin.verifikasi.surat.kesanggupan') ? 'active' : 'completed' }}">
                <div class="step-counter">3</div>
                <div class="step-name text-center">Surat Kesanggupan</div>
            </div>
            <div
                class="stepper-item {{ Route::is('admin.surat.keputusan') || Route::is('admin.menunggu.approve.surat.keputusan') ? 'active' : 'completed' }}">
                <div class="step-counter">4</div>
                <div class="step-name text-center">Surat Keputusan</div>
            </div>
        </div>
    </div>
</div>
