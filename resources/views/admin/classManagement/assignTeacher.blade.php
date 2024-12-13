@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Assign Teacher to Class: {{ $class->name }}</h1>
                </div>
            </div>
        </div>
    </section>

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
                                <option value="{{ $teacher->id }}"
                                    {{ $assignedTeacher && $assignedTeacher->teacher_id === $teacher->id ? 'selected' : '' }}>
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
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($class->students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    {{-- <td>
                                        <form method="post"
                                            action="{{ route('class.removeStudent', ['classId' => $class->id, 'studentId' => $student->id]) }}"
                                            onsubmit="return confirm('Are you sure you want to remove this student from the class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td> --}}
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
