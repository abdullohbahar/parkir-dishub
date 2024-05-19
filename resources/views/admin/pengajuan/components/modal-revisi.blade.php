<div class="modal fade" tabindex="-1" id="modalRevisi">
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
                <form action="{{ route('admin.revisi.dokumen') }}" method="POST">
                    @csrf
                    <label for="" class="form-label">Alasan Revisi</label>
                    <input type="hidden" name="dokumenID" id="revisiDokumenID">
                    <input type="hidden" name="pengajuanID" id="{{ $pengajuan->id }}">
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
