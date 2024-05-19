@extends('pemohon.layout.app')

@section('title')
    Revisi Dokumen Permohonan
@endsection

@push('addons-css')
@endpush

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Revisi Dokumen Permohonan</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Revisi Dokumen Permohonan</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <h2>Data Pemohon</h2>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td style="width: 25% !important">Nama pemohon</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tempat, Tanggal Lahir</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->tempat_lahir }},
                                                    {{ $pengajuan->hasOnePemohon->hasOneProfile->tanggal_lahir }}</td>
                                            </tr>
                                            <tr>
                                                <td>Agama</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->agama }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pendidikan Terakhir</td>
                                                <td>:
                                                    {{ $pengajuan->hasOnePemohon->hasOneProfile->pendidikan_terakhir }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat Pemohon</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <td>No KTP</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->no_ktp }}</td>
                                            </tr>
                                            <tr>
                                                <td>No Telepon</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->no_telepon }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <h2>Data Permohonan</h2>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td style="width: 25% !important">Nama Pemilik
                                                    Toko/Lembaga/Kantor</td>
                                                <td>: {{ $pengajuan->nama_pemilik }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25% !important">Longitude / Latitude</td>
                                                <td>: {{ $pengajuan->longitude }}, {{ $pengajuan->latitude }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25% !important">Alamat Lokasi Parkir</td>
                                                <td>: {{ $pengajuan->alamat_lokasi_parkir }}
                                                </td>
                                            </tr>
                                            <tr
                                                {{ $pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan' ? '' : 'hidden' }}>
                                                <td style="width: 25% !important">Panjang</td>
                                                <td>: {{ $pengajuan->panjang }}
                                                </td>
                                            </tr>
                                            <tr
                                                {{ $pengajuan->hasOneJenisPengajuan->jenis == 'Khusus Parkir' ? '' : 'hidden' }}>
                                                <td style="width: 25% !important">Luas</td>
                                                <td>: {{ $pengajuan->luas }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <h2>Dokumen Permohonan
                                        </h2>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Dokumen</th>
                                                    <th>Status</th>
                                                    <th>Alasan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            @foreach ($pengajuan->hasManyDokumenPengajuan as $dokumen)
                                                <tr>
                                                    <td>
                                                        {{ $dokumen->nama_dokumen }} | <a target="_blank"
                                                            href="{{ $dokumen->file }}">
                                                            Lihat File
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $dokumen->status }}
                                                    </td>
                                                    <td>
                                                        {{ $dokumen->alasan }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            @if ($dokumen->status == 'Revisi')
                                                                <button data-id="{{ $dokumen->id }}"
                                                                    data-namadokumen="{{ $dokumen->nama_dokumen }}"
                                                                    id="revisiBtn"
                                                                    class="btn btn-warning btn-sm text-dark">Revisi
                                                                    Dokumen</button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

    <div class="modal fade" tabindex="-1" id="modalRevisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Revisi</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="{{ route('pemohon.revisi.dokumen') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label for="" class="form-label">Unggah Revisi <span id="namaDokumen"></span></label>
                        <input type="hidden" name="dokumenID" id="revisiDokumenID">
                        <input type="hidden" name="nama_dokumen" id="nama_dokumen">
                        <input type="file" required accept=".pdf, .jpg, .png" onchange="validateFile(this)"
                            name="file" class="form-control">
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
@endsection

@push('addons-js')
    <script src="{{ asset('./assets/js/pages/validate-file.js') }}"></script>
    <script>
        $("body").on("click", "#revisiBtn", function() {
            var id = $(this).data("id")
            var namadokumen = $(this).data("namadokumen")

            $("#revisiDokumenID").val(id)
            $("#namaDokumen").text(namadokumen)
            $("#nama_dokumen").val(namadokumen)
            $("#modalRevisi").modal("show");
        })
    </script>
@endpush
