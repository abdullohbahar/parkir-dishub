<div class="modal fade" tabindex="-1" id="modalBuatJadwal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Buat Jadwal Tanggal <span id="tanggalText"></span></h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <form action="{{ route('admin.store.jadwal.tinjauan.lapangan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                    <input type="hidden" name="tanggal" id="tanggal">
                    <h5>
                        <b>Pilih Jam</b>
                    </h5>
                    <div class="row">

                        <div class="col-6">
                            <label style="width: 100%">
                                <input type="radio" value="08:00 - 10:00" name="jam" class="card-input-element" />
                                <div class="panel panel-default card-input text-center rounded-pill">
                                    <div class="panel-heading centered-element">
                                        <h2 class="pt-1">
                                            08:00 - 10:00
                                        </h2>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label style="width: 100%">
                                <input type="radio" value="10:00 - 12:00" name="jam" class="card-input-element"
                                    required />
                                <div class="panel panel-default card-input text-center rounded-pill">
                                    <div class="panel-heading centered-element">
                                        <h2 class="pt-1">
                                            10:00 - 12:00
                                        </h2>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label style="width: 100%">
                                <input type="radio" value="13:00 - 15:00" name="jam" class="card-input-element" />
                                <div class="panel panel-default card-input text-center rounded-pill">
                                    <div class="panel-heading centered-element">
                                        <h2 class="pt-1">
                                            13:00 - 15:00
                                        </h2>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label style="width: 100%">
                                <input type="radio" value="15:00 - 17:00" name="jam" class="card-input-element" />
                                <div class="panel panel-default card-input text-center rounded-pill">
                                    <div class="panel-heading centered-element">
                                        <h2 class="pt-1">
                                            15:00 - 17:00
                                        </h2>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-5 btn-sm">Submit</button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
