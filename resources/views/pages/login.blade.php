<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengsusulan NIPPPK - Kabupaten Badung</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">

    {{-- VENDOR CDN --}}
    {{-- Font Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    {{-- Bootrtrap --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/bootstrap.css') }}">
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconsax/style.css') }}">

    @vite('resources/css/login.css')
</head>

<body>
    <div class="login-container">
        <div class="header-section mb-4">
            <div class="logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            </div>
            <div class="title">
                <h1>Pengusulan NIPPPK</h1>
                <h2>Kabupaten Badung</h2>
            </div>
        </div>
        <div class="login-section">
            <div class="card border-0">
                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="nik" class="fw-bold col-form-label">NIK</label>
                            <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukkan NIK anda" autofocus autocomplete="off">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="fw-bold col-form-label">Password</label>
                            <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Masukkan password anda" required>
                                    <button class="btn btn-toggle-password isax isax-eye-slash border" type="button"
                                        id="button-addon2"></button>
                                </div>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary d-block w-100 fw-bold">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- VENDOR LOCAL --}}
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    {{-- VENCDOR CDN --}}
    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    {{-- Jquery --}}
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- JS Local --}}
    @vite('resources/js/login.js')
    @vite('resources/js/auth.js')
    @include('components.toastr')
</body>

</html>
