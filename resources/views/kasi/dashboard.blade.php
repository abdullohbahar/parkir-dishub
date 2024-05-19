@extends('kasi.layout.app')

@section('title')
    Permohonan
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
                        Permohonan</h1>
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
                        <li class="breadcrumb-item text-muted">Permohonan</li>
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
                            <div class="card-header">
                                <h1 class="mt-5">Permohonan Perlu Persetujuan</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <table class="table table-borderd table-striped">
                                        <tr>
                                            <th>No</th>
                                            <th>Pemohon</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @forelse ($perluPersetujuan as $perlu)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $perlu->belongsToPengajuan->hasOnePemohon->hasOneProfile->nama }}
                                                <td>{{ $perlu->belongsToPengajuan->hasOneJenisPengajuan->jenis }}</td>
                                                <td>{{ $perlu->status }}</td>
                                                <td>
                                                    <a href="{{ route('kasi.verifikasi.surat.keputusan', $perlu->pengajuan_id) }}"
                                                        class="btn btn-sm btn-info">
                                                        {{ $perlu->status == 'Persetujuan Kasi' ? 'Verifikasi Surat Keputusan' : 'Lihat Surat Keputusan' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center"><b>
                                                        Data Tidak Ada</b></td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="mt-5">Permohonan Telah Disetujui</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <table class="table table-borderd table-striped">
                                        <tr>
                                            <th>No</th>
                                            <th>Pemohon</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @forelse ($telahDisetujui as $setuju)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $setuju->belongsToPengajuan->hasOnePemohon->hasOneProfile->nama }}
                                                <td>{{ $setuju->belongsToPengajuan->hasOneJenisPengajuan->jenis }}</td>
                                                <td>
                                                    <a href="{{ route('kasi.verifikasi.surat.keputusan', $setuju->pengajuan_id) }}"
                                                        class="btn btn-sm btn-success">
                                                        Lihat Surat Keputusan
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center"><b>
                                                        Data Tidak Ada</b></td>
                                            </tr>
                                        @endforelse
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
@endsection

@push('addons-js')
@endpush
