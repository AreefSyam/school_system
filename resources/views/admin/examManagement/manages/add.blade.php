@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><b>Register</b>: New Examination</h1>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Insert The Details Below</h3>
                        </div>
                        <form method="post" action="{{ route('examManagement.add.post') }}">
                            @csrf
                            <div class="card-body">
                                <!-- Examination Name -->
                                <div class="form-group">
                                    <label>Examination Name <span class="text-danger">*</span></label>
                                    <input type="text" name="exam_name" class="form-control"
                                        placeholder="Enter Examination Name" value="{{ old('exam_name') }}" required>
                                    @error('exam_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Exam Type -->
                                <div class="form-group">
                                    <label>Exam Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="exam_type_id" required>
                                        <option value="" disabled selected>-- Select Exam Type --</option>
                                        @foreach ($exam_types as $exam_type)
                                        <option value="{{ $exam_type->id }}" {{ old('exam_type_id')==$exam_type->id ?
                                            'selected' : '' }}>
                                            {{ $exam_type->exam_type_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('exam_type_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Syllabus -->
                                <div class="form-group">
                                    <label>Syllabus <span class="text-danger">*</span></label>
                                    <select class="form-control" name="syllabus_id" required>
                                        <option value="" disabled selected>-- Select Syllabus --</option>
                                        @foreach ($syllabuses as $syllabus)
                                        <option value="{{ $syllabus->id }}" {{ old('syllabus_id')==$syllabus->id ?
                                            'selected' : '' }}>
                                            {{ $syllabus->syllabus_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('syllabus_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Academic Year -->
                                <div class="form-group">
                                    <label>Academic Year <span class="text-danger">*</span></label>
                                    <select class="form-control" name="academic_year_id" required>
                                        <option value="" disabled selected>-- Select Academic Year --</option>
                                        @foreach ($academic_years as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id')==$year->id ?
                                            'selected' : '' }}>
                                            {{ $year->academic_year_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('academic_year_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="card-footer">
                                <a href="{{ route('examManagement.list') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Register New Examination</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
