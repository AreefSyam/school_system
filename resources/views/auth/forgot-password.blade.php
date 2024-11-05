<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            {{-- Alert Message --}}
            @include('messages.alert')

            {{-- <div class="row">
                <div class="card-header text-center">
                    <a href="" class="h1"><b>Forgot Password</b></a>
                </div>
            </div> --}}



            <!-- Header Title Centered -->
            <div class="card-header text-center">
                <p href="" class="h1"><b>Forgot Password</b></p>
            </div>



            <div class="card-body">
                <p class="login-box-msg">Write your email address</p>

                <form action="{{ route('forgot-password.post') }}" method="post">
                    @csrf

                    <!-- Email Field -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="row justify-content-end">
                        <!-- Back Button (Cancel) -->
                        <div class="col-4">
                            <a href="{{ route('login') }}" class="btn btn-light btn-block">
                                Cancel
                            </a>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>


                </form>



            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
