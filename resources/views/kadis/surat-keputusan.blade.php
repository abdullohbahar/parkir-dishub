@extends('kadis.layout.app')

@section('title')
    Surat Keputusan
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
                        Surat Keputusan</h1>
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
                        <li class="breadcrumb-item text-muted">Surat Keputusan</li>
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
                                    <div class="col-12">
                                        <div class="col-12">
                                            @php
                                                if ($suratKeputusan->file) {
                                                    $route = route('preview.surat.keputusan', $pengajuanID);
                                                } else {
                                                    $route = route('surat.keputusan', $pengajuanID);
                                                }
                                            @endphp
                                            <iframe style="width: 100%; height: 700px;" src="{{ $route }}"
                                                frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                                @if ($suratKeputusan->status == 'Persetujuan Kadis')
                                    <form action="{{ route('kadis.kirim.surat.keputusan.kekadis', $pengajuan->id) }}"
                                        method="POST" id="myForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mt-5">
                                            <div class="col-12 mt-5">
                                                <h2>Setujui dan Proses TTE</h2>
                                                <button type="submit" class="btn btn-success mt-5">Setujui & Kirim</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
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
    <script src="{{ asset('./assets/js/pages/validate-file.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/form-confirm.js') }}"></script>
@endpush
