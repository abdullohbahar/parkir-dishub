@php
    $role = auth()->user()->role;
@endphp

@extends("$role.layout.app")

@section('title')
    Profile
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
                        Profile</h1>
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
                        <li class="breadcrumb-item text-muted">Profile</li>
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
                            <div class="card-header pt-5">
                                <h1>Profil</h1>
                                <div class="card-toolbar">
                                    <a href="{{ route('profile.edit', $userID) }}" class="btn btn-warning text-dark">
                                        Ubah Profile
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-12 text-center">
                                        <img src="{{ $user->hasOneProfile?->foto_profile }}" id="preview-foto-profile"
                                            class="rounded-3 w-50" alt="user">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <b>
                                                        Nama
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->hasOneProfile?->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        No KTP
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->hasOneProfile?->no_ktp }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Alamat
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->hasOneProfile?->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        No Telepon
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->hasOneProfile?->no_telepon }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Email
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Username
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>
                                                        Role
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->role }}</td>
                                            </tr>
                                            {{-- <tr>
                                                <td>
                                                    <b>
                                                        Jabatan
                                                    </b>
                                                </td>
                                                <td>
                                                    <b>:</b>
                                                </td>
                                                <td>{{ $user->jabatan ?? '-' }}</td>
                                            </tr> --}}
                                            @if ($user->hasOneProfile?->no_sertifikat)
                                                <tr>
                                                    <td>
                                                        <b>
                                                            No Sertifikat
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b>:</b>
                                                    </td>
                                                    <td>{{ $user->hasOneProfile?->no_sertifikat }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->hasOneProfile?->masa_berlaku_sertifikat)
                                                <tr>
                                                    <td>
                                                        <b>
                                                            Masa Berlaku Sertifikat
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b>:</b>
                                                    </td>
                                                    <td>{{ $user->hasOneProfile?->masa_berlaku_sertifikat }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->hasOneProfile?->tingkatan)
                                                <tr>
                                                    <td>
                                                        <b>
                                                            Tingkatan
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b>:</b>
                                                    </td>
                                                    <td>{{ $user->hasOneProfile?->tingkatan }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->hasOneProfile?->sekolah_terakhir)
                                                <tr>
                                                    <td>
                                                        <b>
                                                            Sekolah Terakhir
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b>:</b>
                                                    </td>
                                                    <td>{{ $user->hasOneProfile?->sekolah_terakhir }}</td>
                                                </tr>
                                            @endif
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
