<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\icons\KAJILAH_V2.svg') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <style>
        body {
            background-image: url('assets/images/bg23.svg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        .register-box {
            background-color: rgba(169, 216, 184, 0.8);
            /* Slight transparency */
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <!-- Alert Message -->
        @include('messages.alert')
        <!-- card forgot password -->
        <div class="card card-outline card-success">
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

                    <!-- Button -->
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <a href="{{ route('login') }}" class="btn btn-light btn-block">
                                Cancel
                            </a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
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
