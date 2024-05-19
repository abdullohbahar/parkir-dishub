@extends('pemohon.layout.app')

@section('title')
    Buat Pengajuan Baru
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
                        Buat Pengajuan Baru</h1>
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
                        <li class="breadcrumb-item text-muted">Buat Pengajuan Baru</li>
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
                            <div class="card-header pt-5">
                                <h1>Pilih Jenis Pengajuan</h1>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pemohon.store.jenis.pengajuan') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="" class="form-label">Jenis Pengajuan</label>
                                            <select name="jenis_pengajuan_id" id="jenis_pengajuan" class="form-control">
                                                <option value="">-- Pilih Jenis Pengajuan --</option>
                                                @foreach ($jenisPengajuans as $jenis)
                                                    <option value="{{ $jenis->id }}">{{ $jenis->jenis }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 mt-3" id="tipe_section" hidden>
                                            <label for="" class="form-label">Tipe Pengajuan</label>
                                            <select name="tipe_pengajuan_id" id="tipe_pengajuan" class="form-control">
                                                <option value="">-- Pilih tipe Pengajuan --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" id="button_section" hidden>
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
    <script src="{{ asset('./assets/js/pages/pemohon/pilih-jenis-pengajuan.js') }}"></script>
@endpush
