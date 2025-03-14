@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Teacher</h1>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.list') }}">Teacher List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.add') }}"> Add Teacher </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Insert The Detail Below</h3>
                </div>

                <form method="post" action="{{ route('teacher.add.post') }}">
                    @csrf
                    <div class="card-body">
                        <!-- Name -->
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name"
                                value="{{ old('name') }}" required>
                            @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email"
                                value="{{ old('email') }}" required>
                            @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <!-- Password Input -->
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif

                        <div class="card-footer text-right">
                            <a href="{{ route('teacher.list') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const passwordIcon = this.querySelector('i');

    // Toggle the password field type
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
});
</script>
@endsection
