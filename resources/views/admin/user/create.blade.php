@extends('admin.layout.app')

@section('title')
    Tambah user
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
                        Tambah user</h1>
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
                        <li class="breadcrumb-item text-muted">Tambah user</li>
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
                                <form action="{{ route('user.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama"
                                                class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                value="{{ old('nama') }}">
                                            @error('nama')
                                                <div class="invalid-feedback text-capitalize">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback text-capitalize">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Username</label>
                                            <input type="text" name="username"
                                                class="form-control @error('username') is-invalid @enderror" id="username"
                                                value="{{ old('username') }}">
                                            @error('username')
                                                <div class="invalid-feedback text-capitalize">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Role</label>
                                            <select name="role" class="form-control @error('role') is-invalid @enderror"
                                                id="role" id="">
                                                <option value="">-- Pilih Role --</option>
                                                <option {{ $kasi ? 'hidden' : '' }} value="kasi">Kasi</option>
                                                <option {{ $kabid ? 'hidden' : '' }} value="kabid">Kabid</option>
                                                <option {{ $kadis ? 'hidden' : '' }} value="kadis">Kadis</option>
                                                <option value="pemohon">Pemohon</option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback text-capitalize">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Password</label>
                                            <div class="input-group mb-5">
                                                <input type="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" autocomplete="new-password">
                                                <span class="input-group-text view-password" id="basic-addon2">
                                                    <i id="icon" class="fas fa-eye"></i>
                                                </span>
                                                @error('password')
                                                    <div class="invalid-feedback text-capitalize">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            @include('admin.user.components.password-requirement')
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                                            <label for="" class="form-label">Konfirmasi Password</label>
                                            <div class="input-group mb-5">
                                                <input type="password" name="password_confirmation"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    id="password_confirmation" autocomplete="new-password">
                                                <span class="input-group-text view-password-confirmation" id="basic-addon2">
                                                    <i id="icon-password-confirmation" class="fas fa-eye"></i>
                                                </span>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback text-capitalize">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div style="float: right">
                                                <button type="submit" class="btn btn-success">Simpan</button>
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
        var role = '{{ old('role') }}'

        $("#role").val(role)
    </script>

    <script src="{{ asset('./assets/js/pages/view-password.js') }}"></script>
@endpush
