<div class="card mb-5">
    <div class="card-header">
        <div class="stepper-wrapper">
            <div class="stepper-item {{ Route::is('pemohon.pilih.jenis.pengajuan') ? 'active' : 'completed' }}">
                <div class="step-counter">1</div>
                <div class="step-name text-center">Pilih Jenis Pengajuan</div>
            </div>
            <div class="stepper-item {{ Route::is('pemohon.input.data.permohonan') ? 'active' : 'completed' }}">
                <div class="step-counter">2</div>
                <div class="step-name text-center">Input Data Permohonan</div>
            </div>
            <div
                class="stepper-item {{ Route::is('pemohon.upload.dokumen.pengajuan') || Route::is('pemohon.wait.verification.dokumen.pengajuan') ? 'active' : 'completed' }}">
                <div class="step-counter">3</div>
                <div class="step-name text-center">Dokumen Permohonan</div>
            </div>
            <div class="stepper-item {{ Route::is('pemohon.jadwal.tinjauan.lapangan') ? 'active' : 'completed' }}">
                <div class="step-counter">4</div>
                <div class="step-name text-center">Jadwal Tinjauan Lapangan</div>
            </div>
            <div class="stepper-item {{ Route::is('pemohon.create.surat.kesanggupan') ? 'active' : 'completed' }}">
                <div class="step-counter">4</div>
                <div class="step-name text-center">Surat Kesanggupan</div>
            </div>
        </div>
    </div>
</div>
