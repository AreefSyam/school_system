@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Assign Teacher to Class: {{ $class->name }} -  {{ $class->academicYear->academic_year_name ?? 'N/A' }}</h1>
                </div>
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
                <a href="{{ route('class.list') }}"> Class List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('class.assignTeacher', $class->id) }}"> Assign Teacher Class </a>
            </li>
        </ol>
    </nav>

    <!-- Assigned Teacher Information -->
    @if ($assignedTeacher)
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Currently Assigned Teacher</h3>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $assignedTeacher->teacher->name }}</p>
                    <p><strong>Email:</strong> {{ $assignedTeacher->teacher->email }}</p>
                </div>
                <div class="card-footer">
                    <form method="post"
                        action="{{ route('class.removeTeacher', ['classId' => $class->id, 'teacherId' => $assignedTeacher->teacher_id]) }}"
                        onsubmit="return confirm('Are you sure you want to remove this teacher from the class?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Remove Teacher</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Assign Teacher Form -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select a Teacher to Assign</h3>
                </div>

                <form method="post" action="{{ route('class.assignTeacher.post', $class->id) }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="teacher_id">Teacher</label>
                            <select name="teacher_id" id="teacher_id" class="form-control">
                                <option value="">-- Select a Teacher --</option>
                                @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $assignedTeacher && $assignedTeacher->teacher_id
                                    === $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('teacher_id'))
                            <span class="text-danger">{{ $errors->first('teacher_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Assign Teacher</button>
                        <a href="{{ route('class.list') }}" class="btn btn-secondary">Back to Class List</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- List of Assigned Students -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students Assigned to Class</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Student Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($class->students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->full_name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
