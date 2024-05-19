@extends('admin.layout.app')

@section('title')
    Buat Jadwal Tinjauan Lapangan
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
                        Buat Jadwal Tinjauan Lapangan</h1>
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
                        <li class="breadcrumb-item text-muted">Buat Jadwal Tinjauan Lapangan</li>
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
                                <h1>Buat Jadwal Tinjauan Lapangan</h1>
                                {{-- <div style="float: right">
                                    <form action="{{ route('pemohon.selesai.revisi', $pengajuan->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Selesai
                                        </button>
                                    </form>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mt-5 mb-5" id="kt_docs_fullcalendar_selectable"></div>
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
    @include('admin.pengajuan.components.modal-buat-jadwal')
    @include('admin.pengajuan.components.modal-detail-jadwal')
@endsection

@push('addons-js')
    {{-- hapus jadwal --}}
    <script>
        var token = $('meta[name="csrf-token"]').attr('content');

        // destroy anak asuh
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });

        $("body").on("click", "#btnHapus", function() {
            var id = $(this).data("id");

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Jadwal akan terhapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/pengajuan/buat-jadwal/destroy/' +
                            id,
                        type: 'DELETE',
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

    <script>
        "use strict";

        // Class definition
        var KTGeneralFullCalendarSelectDemos = function() {
            // Private functions

            var exampleSelect = function() {
                // Define variables
                var calendarEl = document.getElementById('kt_docs_fullcalendar_selectable');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                    },
                    initialDate: new Date(),
                    navLinks: true, // can click day/week names to navigate views
                    selectable: true,
                    selectMirror: true,

                    // Create new event
                    select: function(arg) {
                        console.log(arg.startStr)

                        $("#tanggal").val(arg.startStr)
                        $("#tanggalText").text(arg.startStr)

                        var myModal = new bootstrap.Modal(document.getElementById('modalBuatJadwal'), {
                            keyboard: false
                        })

                        myModal.show()
                    },

                    // Delete event
                    eventClick: function(arg) {
                        var myModal = new bootstrap.Modal(document.getElementById(
                            'modalDetailJadwal'), {
                            keyboard: false
                        })

                        var pengajuanid = arg.event.id

                        $.ajax({
                            url: "/admin/permohonan/detail-jadwal-tinjauan-lapangan/" +
                                pengajuanid,
                            method: "GET",
                            dataType: "JSON",
                            success: function(response) {
                                console.log(response)
                                var alamat = response.data.alamat;
                                var jam_tinjauan = response.data.jam_tinjauan;
                                var tanggal_tinjauan = response.data.tanggal_tinjauan;

                                $("#alamat").text(alamat)
                                $("#jamTinjauan").text(jam_tinjauan)
                                $("#tanggalTinjauan").text(tanggal_tinjauan)
                            },
                        });

                        $("#btnHapus").attr('data-id', pengajuanid)

                        myModal.show()
                    },

                    editable: true,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: <?php echo $arrJadwals; ?>
                });

                calendar.render();
            }

            return {
                // Public Functions
                init: function() {
                    exampleSelect();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function() {
            KTGeneralFullCalendarSelectDemos.init();
        });
    </script>
@endpush
