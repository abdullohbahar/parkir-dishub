@extends('pemohon.layout.app')

@section('title')
    Detail Data Permohonan
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
                        Detail Data Permohonan</h1>
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
                        <li class="breadcrumb-item text-muted">Detail Data Permohonan</li>
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
                                <form action="{{ route('pemohon.store.data.permohonan', $pengajuan->id) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <h2>Data Pemohon</h2>
                                        </div>
                                        <div class="col-12">
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
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h2>Data Permohonan</h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
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
@endpush
