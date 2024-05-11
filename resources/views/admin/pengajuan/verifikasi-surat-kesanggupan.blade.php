@extends('admin.layout.app')

@section('title')
    Verifikasi Surat Kesanggupan
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
                        Verifikasi Surat Kesanggupan</h1>
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
                        <li class="breadcrumb-item text-muted">Verifikasi Surat Kesanggupan</li>
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
                        @include('admin.layout.stepper')
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
                                            Harap melakukan upload surat kesanggupan yang telah diberi tanda tangan kepala
                                            dinas dan cap
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
                                    <div class="col-12">
                                        <div class="col-12">
                                            <iframe style="width: 100%; height: 700px;"
                                                src="{{ asset($pengajuan->hasOneSuratKesanggupan->file) }}"
                                                frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('admin.approve.surat.kesanggupan', $pengajuan->id) }}" method="POST"
                                    id="myForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mt-5">
                                        <div class="col-12 mt-5">
                                            <label for="">File Surat Kesanggupan</label>
                                            <input type="file" name="file" class="form-control" accept=".pdf"
                                                id="">
                                            <button type="submit" class="btn btn-success mt-5">Unggah dan Approve Surat
                                                Kesanggupan</button>
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
    <script src="{{ asset('./assets/js/pages/validate-file.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/form-confirm.js') }}"></script>
@endpush
