<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/sb-admin/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/sb-admin/css/sb-admin-2.min.css">
    <title>{{ $title }}</title>
</head>

<body class="bg-orange">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-5 col-sm-12">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                    @if (session()->has('errors'))
                                        @foreach (session('errors')->all() as $e)
                                            <div class="alert alert-danger" role="alert">
                                                {{ $e }}
                                            </div>
                                        @endforeach
                                    @endif
                                    <form class="user" action="{{ route('authenticate') }}" method="post">
                                        @csrf
                                        <hr>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="emailInput"
                                                aria-describedby="emailHelp" placeholder="Masukkan Email..." required
                                                name="email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="passwordInput" placeholder="Masukkan Password..." required
                                                name="password">
                                        </div>
                                        <button type="submit" class="btn btn-orange btn-user btn-block">
                                            Login
                                        </button>
                                        <a href="{{ route('auth.google') }}" class="btn btn-orange btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login dengan Google
                                        </a>
                                        <hr>
                                        <p class="text-danger my-2">Jika mengalami kendala login hubungi: <span><a
                                                    href="#" target="_blank">0xxxxxxxx</a></span></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/') }}assets/js/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/sb-admin/js/sb-admin-2.min.js"></script>
</body>

</html>
