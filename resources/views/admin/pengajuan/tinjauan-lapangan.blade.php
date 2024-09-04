@extends('admin.layout.app')

@section('title')
    Tinjauan Lapangan
@endsection

@push('addons-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        label {
            width: 100%;
        }

        .card-input-element {
            display: none;
        }

        .card-input {
            margin: 10px;
            padding: 00px;
        }

        .card-input:hover {
            cursor: pointer;
        }

        .card-input {
            box-shadow: 0 0 1px 1px #2ecc71;
            height: 30px;
        }

        .card-input-element:checked+.card-input {
            background-color: #2ecc71;
            color: black;
        }

        /* Style untuk elemen kalender */
        #kt_docs_fullcalendar_selectable {
            position: relative;
            /* Membuat posisi relatif untuk child elements */
        }

        /* Style untuk event title */
        .fc-event-title {
            white-space: normal;
            /* Memastikan text wrap pada title yang panjang */
        }

        /* Style untuk elemen tanggal */
        .fc-daygrid-event {
            display: flex;
            /* Menggunakan Flexbox untuk mengatur tata letak */
            align-items: flex-start;
            /* Mengatur alignment title ke atas */
        }
    </style>
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
                        Tinjauan Lapangan</h1>
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
                        <li class="breadcrumb-item text-muted">Tinjauan Lapangan</li>
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
                        <div class="card">
                            <div class="card-header pt-5">
                                <h1>Tinjauan Lapangan</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Pemohon</td>
                                                <td>: {{ $pengajuan->hasOnePemohon->hasOneProfile->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td>Alamat Tinjauan</td>
                                                <td>
                                                    : {{ $pengajuan->alamat_lokasi_parkir }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Tinjauan</td>
                                                <td>
                                                    : {{ $pengajuan->hasOneJadwalTinjauan->tanggal }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jam Tinjauan</td>
                                                <td>
                                                    : {{ $pengajuan->hasOneJadwalTinjauan->jam }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    @php
                                                        $tanggalTinjauan = $pengajuan->hasOneJadwalTinjauan->getRawOriginal(
                                                            'tanggal',
                                                        );
                                                        $tanggalSekarang = date('Y-m-d');
                                                    @endphp

                                                    @if ($tanggalTinjauan > $tanggalSekarang)
                                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                            class="btn btn-warning btn-sm text-dark">Ubah Jadwal Tinjauan
                                                            Lapangan</button>
                                                    @endif
                                                    {{-- <td>
                                                    <a href="{{ route('download.pemberitahuan.jadwal.tinjauan', $pengajuan->id) }}"
                                                        target="_blank" class="btn btn-info btn-sm">Unduh
                                                        Pemberitahuan Jadwal Tinjauan</a>
                                                </td> --}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-12 mt-5">
                                        @if (!$pengajuan->hasOneJadwalTinjauan->is_review)
                                            <form id="tinjauanLapangan"
                                                action="{{ route('admin.tinjauan.lapangan.selesai', $pengajuan->hasOneJadwalTinjauan->id) }}"
                                                method="POST">
                                                @csrf
                                                <p>Klik tombol ini jika anda telah melakukan tinjauan lapangan</p>
                                                <button class="btn btn-info btn-sm">Sudah melakukan tinjauan
                                                    lapangan</button>
                                            </form>
                                        @endif
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

    @if ($tanggalTinjauan > $tanggalSekarang)
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Jadwal Tinjauan Lapangan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.ubah.tinjauan.lapangan', $pengajuan->hasOneJadwalTinjauan->id) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label" for="">Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" value="{{ $tanggalTinjauan }}"
                                        id="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label class="form-label" for="">Jam</label>
                                </div>
                                <div class="col-6">
                                    <label style="width: 100%">
                                        <input type="radio" value="08:00 - 10:00" name="jam"
                                            class="card-input-element" />
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
                                        <input type="radio" value="10:00 - 12:00" name="jam"
                                            class="card-input-element" required />
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
                                        <input type="radio" value="13:00 - 15:00" name="jam"
                                            class="card-input-element" />
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
                                        <input type="radio" value="15:00 - 17:00" name="jam"
                                            class="card-input-element" />
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('addons-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangkap formulir saat di-submit
            var form = document.getElementById(
                'tinjauanLapangan'); // Ganti 'tinjauanLapangan' dengan ID formulir Anda

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah formulir untuk langsung di-submit

                // Menampilkan konfirmasi SweetAlert
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Klik "Ya" untuk konfirmasi.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengklik "Ya", formulir akan di-submit
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
