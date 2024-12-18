@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Register</b>: New Student</h1>
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
                <a href="{{ route('studentManagement.list') }}">Student List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('studentManagement.add') }}">Add New Student </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Insert The Details Below </h3>
                </div>
                <form method="post" action="{{ route('studentManagement.add.post') }}">
                    @csrf
                    <div class="card-body">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" placeholder="Enter Full Name"
                                value="{{ old('full_name') }}" required>
                            @error('full_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- IC Number -->
                        <div class="form-group">
                            <label>IC Number <span class="text-danger">*</span></label>
                            <input type="text" name="ic_number" class="form-control" placeholder="Enter IC Number"
                                value="{{ old('ic_number') }}" required>
                            @error('ic_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select class="form-control" name="gender" required>
                                <option value="" disabled selected>-- Select Gender --</option>
                                @foreach ($genders as $gender)
                                <option value="{{ $gender }}" {{ old('gender')==$gender ? 'selected' : '' }}>{{
                                    $gender }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control"
                                value="{{ old('date_of_birth') }}" required max="{{ date('Y-m-d') }}">
                            @error('date_of_birth')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="2"
                                placeholder="Enter Address">{{ old('address') }}</textarea>
                            @error('address')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Enrollment Date -->
                        <div class="form-group">
                            <label>Enrollment Date <span class="text-danger">*</span></label>
                            <input type="date" name="enrollment_date" class="form-control"
                                value="{{ old('enrollment_date') }}" required max="{{ date('Y-m-d') }}">
                            @error('enrollment_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Form Buttons -->
                    <div class="card-footer text-right">
                        <a href="{{ route('studentManagement.list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Register New Student</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</div>
@endsection
