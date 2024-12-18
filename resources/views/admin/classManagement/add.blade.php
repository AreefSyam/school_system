@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><strong>Register</strong> New Class</h1>
                </div>
                <div class="col-sm-6">
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
                <a href="{{ route('class.list') }}"> Class List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('class.add') }}"> Add New Class </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Insert The Details Below</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="{{ route('class.add.post') }}">
                    @csrf
                    <div class="card-body">
                        <!-- Class Name -->
                        <div class="form-group">
                            <label>Class Name</label>
                            <input type="text" name="className" class="form-control" placeholder="Enter Class Name"
                                value="{{ old('className') }}" required>
                            @if($errors->has('className'))
                            <span class="text-danger">{{ $errors->first('className') }}</span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="" disabled selected>-- Select Status --</option>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                            @if($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>

                        <!-- Grade Level -->
                        <div class="form-group">
                            <label>Grade Level</label>
                            <select class="form-control" name="grade_level_id" required>
                                <option value="" disabled selected>-- Select Grade Level --</option>
                                @foreach($gradeLevels as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('grade_level_id'))
                            <span class="text-danger">{{ $errors->first('grade_level_id') }}</span>
                            @endif
                        </div>

                        <!-- Academic Year Selection -->
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select class="form-control" name="academic_year_id" required>
                                <option value="" disabled selected>-- Select Academic Year --</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->academic_year_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('academic_year_id'))
                            <span class="text-danger">{{ $errors->first('academic_year_id') }}</span>
                            @endif
                        </div>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer  text-right">
                        <a href="{{ route('class.list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Register New Class</button>
                    </div>
                </form>
            </div>

        </div>
    </section>

</div>
@endsection
