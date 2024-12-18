@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Existing Examination</h1>
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
                <a href="{{ route('examManagement.list') }}">Examination List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('examManagement.edit', $exam->id) }}">Edit Exam: {{ $exam->exam_name }} </a>
            </li>
        </ol>
    </nav>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                    <!-- General form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Adjust The Details Below</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- Form start -->
                        <form method="post" action="{{ route('examManagement.edit.post', $exam->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Examination Name -->
                                <div class="form-group">
                                    <label>Examination Name</label>
                                    <input type="text" name="exam_name" class="form-control"
                                        value="{{ old('exam_name', $exam->exam_name) }}" required>
                                    @if($errors->has('exam_name'))
                                    <span class="text-danger">{{ $errors->first('exam_name') }}</span>
                                    @endif
                                </div>

                                <!-- Exam Type -->
                                <div class="form-group">
                                    <label>Exam Type</label>
                                    <select name="exam_type_id" class="form-control" required>
                                        <option value="" disabled>Select Exam Type</option>
                                        @foreach($exam_types as $exam_type)
                                        <option value="{{ $exam_type->id }}" {{ old('exam_type_id', $exam->exam_type_id)
                                            == $exam_type->id ? 'selected' : '' }}>
                                            {{ $exam_type->exam_type_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('exam_type_id'))
                                    <span class="text-danger">{{ $errors->first('exam_type_id') }}</span>
                                    @endif
                                </div>

                                <!-- Syllabus -->
                                <div class="form-group">
                                    <label>Syllabus</label>
                                    <select name="syllabus_id" class="form-control" required>
                                        <option value="" disabled>Select Syllabus</option>
                                        @foreach($syllabuses as $syllabus)
                                        <option value="{{ $syllabus->id }}" {{ old('syllabus_id', $exam->syllabus_id) ==
                                            $syllabus->id ? 'selected' : '' }}>
                                            {{ $syllabus->syllabus_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('syllabus_id'))
                                    <span class="text-danger">{{ $errors->first('syllabus_id') }}</span>
                                    @endif
                                </div>

                                <!-- Academic Year -->
                                <div class="form-group">
                                    <label>Academic Year</label>
                                    <select name="academic_year_id" class="form-control" required>
                                        <option value="" disabled>Select Academic Year</option>
                                        @foreach($academic_years as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id', $exam->
                                            academic_year_id) == $year->id ? 'selected' : '' }}>
                                            {{ $year->academic_year_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('academic_year_id'))
                                    <span class="text-danger">{{ $errors->first('academic_year_id') }}</span>
                                    @endif
                                </div>

                                <!-- Start Date -->
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date', $exam->start_date) }}" required>
                                    @if($errors->has('start_date'))
                                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                    @endif
                                </div>

                                <!-- End Date -->
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date', $exam->end_date) }}" required>
                                    @if($errors->has('end_date'))
                                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="card-footer">
                                <a href="{{ route('examManagement.list') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
        </div>
    </section>
</div>
@endsection
