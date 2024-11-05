@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Subject</h1>
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
                    <!-- General form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Adjust Subject Details Below</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- Form start -->
                        <form method="post" action="{{ route('subjectManagement.edit.post', $subject->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Subject Name -->
                                <div class="form-group">
                                    <label>Subject Name <span class="text-danger">*</span></label>
                                    <input type="text" name="subject_name" class="form-control"
                                        value="{{ old('subject_name', $subject->subject_name) }}" required>
                                    @if($errors->has('subject_name'))
                                    <span class="text-danger">{{ $errors->first('subject_name') }}</span>
                                    @endif
                                </div>

                                <!-- Syllabus -->
                                <div class="form-group">
                                    <label>Syllabus <span class="text-danger">*</span></label>
                                    <select name="syllabus_id" class="form-control" required>
                                        <option value="" disabled>Select Syllabus</option>
                                        @foreach($syllabuses as $syllabus)
                                        <option value="{{ $syllabus->id }}" {{ old('syllabus_id', $subject->syllabus_id)
                                            == $syllabus->id ? 'selected' : '' }}>
                                            {{ $syllabus->syllabus_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('syllabus_id'))
                                    <span class="text-danger">{{ $errors->first('syllabus_id') }}</span>
                                    @endif
                                </div>

                                <!-- Grade Levels -->
                                <div class="form-group">
                                    <label>Grade Levels <span class="text-danger">*</span></label>
                                    <div>
                                        @foreach ($gradeLevels as $grade)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="grade_level_id[]"
                                                value="{{ $grade->id }}" {{ $subject->gradeLevels->where('id',
                                            $grade->id)->where('pivot.active', 1)->isNotEmpty() ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $grade->grade_name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('grade_level_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="academic_year_id">Academic Year</label>
                                    <select name="academic_year_id" class="form-control" required>
                                        <option value="" disabled selected>Select Academic Year</option>
                                        @foreach ($academic_years as $year)
                                            <option value="{{ $year->id }}" {{ old('academic_year_id', $subject->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                                {{ $year->academic_year_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>



                                <!-- Buttons -->
                                <div class="card-footer">
                                    <a href="{{ route('subjectManagement.list') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
