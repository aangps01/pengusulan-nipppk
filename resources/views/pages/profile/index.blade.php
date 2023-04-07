@extends('layouts.dashboard-base')

@section('content')
    <div class="page-heading mb-3">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>My Profile</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header pb-2">
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="img-container">
                                <img src="{{ $user->profile_photo_url }}" alt="Profile Picture"
                                    class="img-fluid rounded-circle" width="80px">
                            </div>
                            <div class="detail-profile ms-4">
                                <h6 class="m-0 fs-5">{{ $user->name }}</h6>
                                <p class="m-0">{{ $user->rolename }}</p>
                            </div>
                        </div>
                        <hr>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <p class="mb-4 fs-6 fw-bold fst-italic">Detail Profile</p>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="{{ $user->name }}"
                                    disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" value="{{ $user->email }}"
                                    disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="rolename" class="form-label">Role</label>
                                <input type="text" class="form-control" id="rolename" value="{{ $user->rolename }}"
                                    disabled>
                            </div>
                            <hr class="mt-4">
                            <p class="mb-4 fs-6 fw-bold fst-italic">Ubah Password</p>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Masukkan password baru anda" required>
                                    <button class="btn btn-toggle-password isax isax-eye-slash border" type="button"
                                        id="button-addon2"></button>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="Konfirmasi password baru anda" required>
                                    <button class="btn btn-toggle-password isax isax-eye-slash border" type="button"
                                        id="button-addon3"></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary float-end">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@push('scripts')
    @vite('resources/js/auth.js')
@endpush
