@extends('pemohon.layout.app')

@section('title')
    Input Data Permohonan
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
                        Input Data Permohonan</h1>
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
                        <li class="breadcrumb-item text-muted">Input Data Permohonan</li>
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
                                                <label for="" class="form-label">Nama Pemilik
                                                    Toko/Lembaga/Kantor</label>
                                                <input type="text"
                                                    class="form-control @error('nama_pemilik') is-invalid @enderror"
                                                    name="nama_pemilik" value="{{ old('nama_pemilik') }}" id="">
                                                @error('nama_pemilik')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 mt-5">
                                                    <label for="" class="form-label">Tentukan Lokasi Pengelolaan
                                                        Parkir. <i style="color: red">Anda Harus Berada Dilokasi Untuk
                                                            Menentukan
                                                            Lokasi</i></label>
                                                </div>
                                                <div class="col-6">
                                                    <label for="" class="form-label">longitude</label>
                                                    <input type="text"
                                                        class="form-control @error('longitude') is-invalid @enderror"
                                                        name="longitude" value="{{ old('longitude') }}" required
                                                        id="longitude">
                                                    @error('longitude')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-6">
                                                    <label for="" class="form-label">latitude</label>
                                                    <input type="text"
                                                        class="form-control @error('latitude') is-invalid @enderror"
                                                        name="latitude" value="{{ old('latitude') }}" required
                                                        id="latitude">
                                                    @error('latitude')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <button type="button" style="width: 100%" id="getLocation"
                                                        class="btn btn-sm btn-info">Klik
                                                        untuk mengambil
                                                        longitude dan latitude lokasi</button>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <button type="button" style="width: 100%" id="seeLocation"
                                                        class="btn btn-sm btn-primary">Lihat Lokasi</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6 mt-3">
                                            <label for="" class="form-label">Alamat Parkir</label>
                                            <textarea name="alamat_lokasi_parkir" class="form-control @error('alamat_lokasi_parkir') is-invalid @enderror"
                                                style="width: 100%" rows="2">{{ old('alamat_lokasi_parkir') }}</textarea>
                                            @error('alamat_lokasi_parkir')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6 mt-3">
                                            <label for="" class="form-label">Lokasi Parkir</label>
                                            <textarea name="lokasi_pengelolaan_parkir" class="form-control @error('lokasi_pengelolaan_parkir') is-invalid @enderror"
                                                style="width: 100%" rows="2" placeholder="Misal: Utara Pasar">{{ old('lokasi_pengelolaan_parkir') }}</textarea>
                                            @error('lokasi_pengelolaan_parkir')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-3"
                                            {{ $pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan' ? '' : 'hidden' }}>
                                            <label for="" class="form-label">Panjang</label>
                                            <div class="input-group mb-3">
                                                <input type="number" name="panjang"
                                                    class="form-control @error('panjang') is-invalid @enderror"
                                                    value="{{ old('panjang') }}" id="">
                                                <span class="input-group-text" id="basic-addon2">m</span>
                                            </div>
                                            @error('panjang')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-3"
                                            {{ $pengajuan->hasOneJenisPengajuan->jenis == 'Khusus Parkir' ? '' : 'hidden' }}>
                                            <label for="" class="form-label">Luas</label>
                                            <div class="input-group mb-3">
                                                <input type="number" name="luas"
                                                    class="form-control @error('luas') is-invalid @enderror"
                                                    value="{{ old('luas') }}" id="">
                                                <span class="input-group-text" id="basic-addon2">m<sup>2</sup></span>
                                            </div>
                                            @error('luas')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
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

        $("#seeLocation").on("click", function() {
            var latitude = $("#latitude").val()
            var longitude = $("#longitude").val()

            var url = `https://www.google.com/maps?q=${latitude},${longitude}`
            window.open(url, '_blank').focus()
        })
    </script>
    <script src="{{ asset('./assets/js/pages/pemohon/pilih-jenis-pengajuan.js') }}"></script>
@endpush
