@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-7">
                    <h1>Edit Class: {{ $class->name }}</h1>
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
                <a href="{{ route('class.edit',  $class->id) }}"> Edit Class </a>
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
                <!-- Form start -->
                <form method="post" action="{{ route('class.edit.post', $class->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Class Name -->
                        <div class="form-group">
                            <label>Class Name</label>
                            <input type="text" name="className" class="form-control"
                                value="{{ old('className', $class->name) }}" required>
                            @if($errors->has('className'))
                            <span class="text-danger">{{ $errors->first('className') }}</span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="" disabled>Select Status</option>
                                <option value="0" {{ old('status', $class->status) == 0 ? 'selected' : ''
                                    }}>Active</option>
                                <option value="1" {{ old('status', $class->status) == 1 ? 'selected' : ''
                                    }}>Inactive</option>
                            </select>
                            @if($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>

                        <!-- Grade Level -->
                        <div class="form-group">
                            <label>Grade Level</label>
                            <select name="grade_level_id" class="form-control" required>
                                <option value="" disabled>Select Grade Level</option>
                                @foreach($gradeLevels as $grade)
                                <option value="{{ $grade->id }}" {{ old('grade_level_id', $class->
                                    grade_level_id) == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->grade_name }}
                                </option>
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
                                <option value="" disabled>Select Academic Year</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $class->
                                    academic_year_id)
                                    == $year->id ? 'selected' : '' }}>
                                    {{ $year->academic_year_name }}
                                </option>
                                @endforeach
                            </select>
                            @if($errors->has('academic_year_id'))
                            <span class="text-danger">{{ $errors->first('academic_year_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card-footer text-right">
                        <a href="{{ route('class.list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
