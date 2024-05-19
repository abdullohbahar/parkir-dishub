<div class="modal fade" tabindex="-1" id="modalDetailJadwal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Jadwal</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tr>
                                <td>Alamat</td>
                                <td>
                                    : <span id="alamat"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Tinjauan</td>
                                <td>
                                    : <span id="tanggalTinjauan"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Jam Tinjauan</td>
                                <td>
                                    : <span id="jamTinjauan"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-12">
                        Klik tombol dibawah ini jika anda ingin menghapus jadwal ini <br>
                        <button id="btnHapus" class="btn btn-sm btn-danger">
                            Hapus
                        </button>
                    </div>
                </div> --}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
