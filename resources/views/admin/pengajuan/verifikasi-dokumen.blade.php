@extends('admin.layout.app')

@section('title')
    Verifikasi Dokumen Permohonan
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
                        Verifikasi Dokumen Permohonan</h1>
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
                        <li class="breadcrumb-item text-muted">Verifikasi Dokumen Permohonan</li>
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
                        <div class="card">
                            <div class="card-body">
                                <h2>
                                    <b>Data Permohonan</b>
                                </h2>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td>
                                                    <b>
                                                        Jenis Pengajuan
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOneJenisPengajuan?->jenis }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Nama Pemohon
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->nama }} </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Tempat, Tanggal Lahir
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->ttl }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Agama
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->agama }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Pendidikan Terakhir
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->pendidikan_terakhir }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        No KTP
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->no_ktp }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        No Telepon
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->no_telepon }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Alamat Pemohon
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->hasOnePemohon?->hasOneProfile?->alamat }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td>
                                                    <b>
                                                        Lokasi Pengelolaan Parkir
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->longitude }}, {{ $pengajuan->latitude }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Alamat Lokasi Parkir
                                                    </b>
                                                </td>
                                                <td>: {{ $pengajuan->alamat_lokasi_parkir }}</td>
                                            </tr>
                                            <tr>
                                                @if ($pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan')
                                                    <td>
                                                        <b>
                                                            Panjang
                                                        </b>
                                                    </td>
                                                    <td>: {{ $pengajuan->panjang }}</td>
                                                @else
                                                    <td>
                                                        <b>
                                                            Luas
                                                        </b>
                                                    </td>
                                                    <td>: {{ $pengajuan->luas }}</td>
                                                @endif
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h2>Dokumen Permohonan</h2>
                                <div class="row mb-3" {{ $rejectButton }}>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-danger" id="rejectBtn">Tolak Permohonan</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Dokumen</th>
                                                    <th>File</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengajuan->hasManyDokumenPengajuan as $dokumen)
                                                    <tr>
                                                        <td>{{ $dokumen->nama_dokumen }}</td>
                                                        <td>
                                                            @if ($dokumen->getRawOriginal('file'))
                                                                <a href="{{ $dokumen->file }}" class="btn btn-primary"
                                                                    target="_blank">Lihat File</a>
                                                            @else
                                                                Pemohon belum melakukan upload dokumen
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!$dokumen->is_approved)
                                                                @if (!$dokumen->alasan)
                                                                    <button class="btn btn-info" id="approveBtn"
                                                                        data-id="{{ $dokumen->id }}">Setujui</button>
                                                                    <button class="btn btn-warning text-dark"
                                                                        data-id="{{ $dokumen->id }}"
                                                                        id="revisiBtn">Revisi</button>
                                                                @elseif($dokumen->status == 'Revisi')
                                                                    <span
                                                                        class='badge badge-lg badge-warning text-dark mt-3'>Dokumen
                                                                        Perlu
                                                                        Revisi</span> <br>
                                                                    <b>
                                                                        Alasan: {{ $dokumen->alasan }}
                                                                    </b>
                                                                @elseif($dokumen->status == 'Ditolak')
                                                                    <span
                                                                        class='badge badge-lg badge-warning text-dark mt-3'>Dokumen
                                                                        Ditolak
                                                                    </span>
                                                                    <br>
                                                                    <b>
                                                                        Alasan: {{ $dokumen->alasan }}
                                                                    </b>
                                                                @endif
                                                            @else
                                                                <span class='badge badge-lg badge-info mt-3'>Telah
                                                                    Disetujui</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if ($pengajuan->status != 'Tolak')
                                    <form action="" method="POST">
                                        <div class="row" {{ $nextButton }}>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button class="btn btn-success">Selanjutnya</button>
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
    @include('admin.pengajuan.components.modal-revisi')
    @include('admin.pengajuan.components.modal-tolak')
@endsection

@push('addons-js')
    <script>
        $("body").on("click", "#revisiBtn", function() {
            var id = $(this).data("id");
            console.log(id)

            $("#revisiDokumenID").val(id)
            var myModal = new bootstrap.Modal(document.getElementById('modalRevisi'), {
                keyboard: false
            })

            myModal.show()
        })

        $("body").on("click", "#rejectBtn", function() {

            var myModal = new bootstrap.Modal(document.getElementById('modalTolak'), {
                keyboard: false
            })

            myModal.show()
        })

        $("body").on("click", "#approveBtn", function() {
            var id = $(this).data("id");

            Swal.fire({
                title: 'Apakah anda yakin menyetujui dokumen ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/permohonan/setujui-dokumen/' +
                            id,
                        type: 'GET',
                        success: function(response) {
                            if (response.code == 200) {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location
                                        .reload(); // Refresh halaman setelah mengklik OK
                                });
                            } else if (response.code == 500) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
                                })
                            }
                        }
                    })
                }
            })
        })
    </script>

    <script src="{{ asset('./assets/js/pages/pemohon/pilih-jenis-pengajuan.js') }}"></script>
    <script src="{{ asset('./assets/js/pages/validate-file.js') }}"></script>
    {{-- <script src="{{ asset('./assets/js/pages/form-confirm.js') }}"></script> --}}
@endpush
