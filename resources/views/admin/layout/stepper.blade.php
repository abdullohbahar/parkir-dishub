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
                class="stepper-item {{ Route::is('pemohon.upload.dokumen.pengajuan') || Route::is('pemohon.wait.verification.dokumen.pengajuan') ? 'active' : 'completed' }}">
                <div class="step-counter">3</div>
                <div class="step-name text-center">Tinjauan Lapangan</div>
            </div>
        </div>
    </div>
</div>
