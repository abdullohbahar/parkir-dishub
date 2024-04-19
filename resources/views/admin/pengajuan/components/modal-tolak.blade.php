<div class="modal fade" tabindex="-1" id="modalTolak">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Revisi</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form action="{{ route('admin.tolak.dokumen', $pengajuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h2 class="text-center text-capitalize">
                        <b>
                            Apakah anda yakin menolak dokumen permohonan?
                        </b>
                    </h2>
                    <h3 class="text-center text-capitalize">
                        <b>
                            Seluruh Dokumen Akan Ditolak
                        </b>
                    </h3>
                    <label for="" class="form-label mt-4">Alasan Menolak</label>
                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                    <textarea name="alasan" class="form-control" required id="" style="width: 100%" rows="3"></textarea>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
