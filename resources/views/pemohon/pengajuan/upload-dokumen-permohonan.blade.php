@extends('pemohon.layout.app')

@section('title')
    Upload Dokumen Permohonan
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
                        Upload Dokumen Permohonan</h1>
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
                        <li class="breadcrumb-item text-muted">Upload Dokumen Permohonan</li>
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
                        @include('pemohon.layout.stepper')
                    </div>
                    <div class="col-12">
                        <!--begin::Alert-->
                        <div class="alert alert-dismissible bg-warning d-flex flex-column flex-sm-row p-5 mb-10">
                            <!--begin::Icon-->
                            <i class="ki-duotone ki-notification-bing fs-2hx text-dark me-4 mb-5 mb-sm-0"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column text-dark pe-0 pe-sm-10">
                                <!--begin::Title-->
                                <h4 class="mb-2 light">Peringatan !</h4>
                                <!--end::Title-->

                                <!--begin::Content-->
                                <span style="font-size: 11pt">
                                    <ul>
                                        <li>
                                            Untuk surat permohonan harap menggunakan template yang sudah ada. Anda dapat
                                            mengunduhnya dengan klik tombol unduh template surat permohonan
                                        </li>
                                    </ul>
                                </span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <form action="{{ route('pemohon.store.dokumen.pengajuan', $pengajuanID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanID }}">
                                            <label for="" class="form-label">
                                                <a href="" class="btn btn-info btn-sm">Unduh
                                                    Template
                                                    Surat Permohonan</a> <br>
                                                Surat Permohonan
                                            </label>
                                            <div class="input-group mb-3">
                                                <input type="hidden" name="nama_dokumen" value="Surat Permohonan">
                                                <input type="file" name="file" required class="form-control" required
                                                    accept=".pdf" onchange="validatePdf(this)" id="">
                                                <button class="input-group-text btn btn-success"
                                                    type="submit">Upload</button>
                                            </div>
                                            @if ($suratPermohonan)
                                                <a target="_blank" href="{{ $suratPermohonan->file }}">
                                                    Lihat Surat Permohonan Yang Telah Diupload
                                                </a>
                                            @endif
                                            <ol>
                                                <li>File harus berupa PDF</li>
                                            </ol>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <form action="{{ route('pemohon.store.dokumen.pengajuan', $pengajuanID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="nama_dokumen" value="KTP">
                                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanID }}">
                                            <label for="" class="form-label mt-4"><br>
                                                KTP
                                            </label>
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" required class="form-control" required
                                                    accept=".pdf, .jpg, .png" onchange="validateFile(this)" id="">
                                                <button class="input-group-text btn btn-success"
                                                    type="submit">Upload</button>

                                            </div>
                                            @if ($ktp)
                                                <a target="_blank" href="{{ $ktp->file }}">
                                                    Lihat Surat Permohonan Yang Telah Diupload
                                                </a>
                                            @endif
                                            <ol>
                                                <li>File harus berupa JPG, PNG, PDF</li>
                                            </ol>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <form action="{{ route('pemohon.store.dokumen.pengajuan', $pengajuanID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="nama_dokumen" value="Pas Foto">
                                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanID }}">
                                            <label for="" class="form-label mt-4"><br>
                                                Pas Foto
                                            </label>
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" required class="form-control" required
                                                    accept=".pdf, .jpg, .png" onchange="validateFile(this)" id="">
                                                <button class="input-group-text btn btn-success"
                                                    type="submit">Upload</button>

                                            </div>
                                            @if ($pasFoto)
                                                <a target="_blank" href="{{ $pasFoto->file }}">
                                                    Lihat Surat Permohonan Yang Telah Diupload
                                                </a>
                                            @endif
                                            <ol>
                                                <li>File harus berupa JPG, PNG, PDF</li>
                                            </ol>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <form action="{{ route('pemohon.store.dokumen.pengajuan', $pengajuanID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="nama_dokumen" value="Denah">
                                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanID }}">
                                            <label for="" class="form-label mt-4"><br>
                                                Gambar Denah Lokasi
                                            </label>
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" required class="form-control"
                                                    required accept=".pdf, .jpg, .png" onchange="validateFile(this)"
                                                    id="">
                                                <button class="input-group-text btn btn-success"
                                                    type="submit">Upload</button>

                                            </div>
                                            @if ($denah)
                                                <a target="_blank" href="{{ $denah->file }}">
                                                    Lihat Surat Permohonan Yang Telah Diupload
                                                </a>
                                            @endif
                                            <ol>
                                                <li>File harus berupa JPG, PNG, PDF</li>
                                            </ol>
                                        </form>
                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <form action="{{ route('pemohon.store.dokumen.pengajuan', $pengajuanID) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="nama_dokumen" value="Rekom">
                                            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanID }}">
                                            <label for="" class="form-label mt-4"><br>
                                                Rekom Dari Pemilik Usaha/Instansi
                                            </label>
                                            <div class="input-group mb-3">
                                                <input type="file" name="file" required class="form-control"
                                                    required accept=".pdf" onchange="validatePdf(this)" id="">
                                                <button class="input-group-text btn btn-success"
                                                    type="submit">Upload</button>

                                            </div>
                                            @if ($rekom)
                                                <a target="_blank" href="{{ $rekom->file }}">
                                                    Lihat Surat Permohonan Yang Telah Diupload
                                                </a>
                                            @endif
                                            <ol>
                                                <li>File harus berupa PDF</li>
                                            </ol>
                                        </form>
                                    </div>
                                </div>
                                <form action="{{ route('pemohon.next.dokumen.pengajuan', $pengajuanID) }}" method="POST"
                                    id="myForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-success btn-sm mt-3" id="submitBtn"
                                                type="submit">Selanjutnya</button>
                                        </div>
                                    </div>
                                </form>
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
@endsection

@push('addons-js')
    <script>
        $("#getLocation").on("click", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    $("#longitude").val(longitude)
                    $("#latitude").val(latitude)
                });
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
        })
    </script>

    <script src="{{ asset('./assets/js/pages/pemohon/pilih-jenis-pengajuan.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/validate-file.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/form-confirm.js') }}"></script>
@endpush
