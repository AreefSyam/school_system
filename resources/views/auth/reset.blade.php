<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Set Title -->
    <title>Reset Password</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\icons\KAJILAH_V2.svg') }}" />

    <style>
        body {
            background-image: url('{{asset(' assets/images/bg23.svg') }}');
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
        <div class="card card-outline card-success">
            <!-- Alert Message -->
            @include('messages.alert')

            <!-- Header Title -->
            <div class="card-header text-center">
                <p href="" class="h1"><b>Reset Password</b></p>
            </div>

            <!-- Body -->
            <div class="card-body">
                <p class="login-box-msg">Reset password for: {{ $email }}</p>
                <form action="{{ route('reset.post', ['token' => $user->remember_token]) }}" method="post">
                    @csrf
                    <!-- Password Field -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span> <!-- Use fa-lock instead of fa-password -->
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <!-- Confirm Password Field -->
                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span> <!-- Use fa-lock instead of fa-password -->
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <!-- Button -->
                    <div class="row justify-content-end">
                        <!-- Back Button (Cancel) -->
                        <div class="col-4">
                            <a href="{{ route('login') }}" class="btn btn-light btn-block">
                                Cancel
                            </a>
                        </div>
                        <!-- Submit Button -->
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
