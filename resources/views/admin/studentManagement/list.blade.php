@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Management</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a class="btn btn-primary" href="{{ route('studentManagement.add') }}">Add New Student</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
        </ol>
    </nav>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <h3 class="card-title">Search Student</h3>
                        </div>
                        <!-- form start -->
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Full Name -->
                                    <div class="form-group col-md-3">
                                        <label>Full Name</label>
                                        <input type="text" name="full_name" class="form-control"
                                            placeholder="Enter Full Name" value="{{ Request::get('full_name') }}">
                                    </div>
                                    <!-- Gender -->
                                    <div class="form-group col-md-3">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="gender">
                                            <option value="" disabled selected>-- Select Gender --</option>
                                            @foreach ($genders as $gender)
                                            <option value="{{ $gender }}" {{ old('gender')==$gender ? 'selected' : ''
                                                }}>{{
                                                $gender }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Button -->
                                    <div class="form-group col-md-3">
                                        <button class="btn btn-primary" type="submit"
                                            style="margin-top: 30px">Search</button>
                                        <a href="{{ route('studentManagement.list') }}" class="btn btn-success"
                                            style="margin-top: 30px">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('messages.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Student List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div style="overflow-x: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Student Code</th>
                                            <th>Full Name</th>
                                            <th>Gender</th>
                                            <th>IC Number</th>
                                            <th>Enrollment Date</th>
                                            <th>Updated Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_record as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->student_code }}</td>
                                            <td>{{ $value->full_name }}</td>
                                            <td>{{ $value->gender }}</td>
                                            <td>{{ $value->ic_number }}</td>
                                            <td>{{ date('d-m-Y', strtotime($value->enrollment_date)) }}</td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($value->updated_at)) }}</td>
                                            <td>
                                                <a href="{{ route('studentManagement.edit', $value->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <a href="{{ route('studentManagement.delete', $value->id) }}"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div style="padding: 10px; float: right;">
                                {!! $get_record->appends(Illuminate\Support\Facades\Request::except('page'))->links()
                                !!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
