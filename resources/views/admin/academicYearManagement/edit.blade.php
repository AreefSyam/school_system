@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Academic Year</h1>
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
                <a href="{{ route('academicYear.list') }}">Academic Year List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academicYear.edit',  $academicYear->id) }}">Edit Academic Year: {{ $academicYear->academic_year_name }} </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- General form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update the Details Below</h3>
                </div>
                <!-- /.card-header -->
                <!-- Form start -->
                <form method="post" action="{{ route('academicYear.edit.post', $academicYear->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Academic Year Name -->
                        <div class="form-group">
                            <label>Academic Year Name</label>
                            <input type="text" name="academic_year_name" class="form-control"
                                value="{{ old('academic_year_name', $academicYear->academic_year_name) }}" required>
                            @if($errors->has('academic_year_name'))
                            <span class="text-danger">{{ $errors->first('academic_year_name') }}</span>
                            @endif
                        </div>

                        <!-- Start Date -->
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date', $academicYear->start_date) }}" required>
                            @if($errors->has('start_date'))
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                            @endif
                        </div>

                        <!-- End Date -->
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ old('end_date', $academicYear->end_date) }}" required>
                            @if($errors->has('end_date'))
                            <span class="text-danger">{{ $errors->first('end_date') }}</span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="" disabled>Select Status</option>
                                <option value="0" {{ (old('status')==='0' || $academicYear->status == 0) ?
                                    'selected' : '' }}>Active</option>
                                <option value="1" {{ (old('status')==='1' || $academicYear->status == 1) ?
                                    'selected' : '' }}>Inactive</option>
                            </select>
                            @if($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>


                        <!-- Buttons -->
                        <div class="card-footer">
                            <a href="{{ route('academicYear.list') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
