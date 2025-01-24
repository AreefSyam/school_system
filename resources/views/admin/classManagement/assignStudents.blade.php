@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Assign Students to Class: {{ $class->name }} - {{ $class->academicYear->academic_year_name ??
                        'N/A' }}</h1>
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
                <a href="{{ route('class.assignStudents', $class->id) }}"> Assign Student Class </a>
            </li>
        </ol>
    </nav>

    <!-- Assign Students Form with Search and Pagination -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Students to Assign</h3>
                    <div class="card-tools">
                        <!-- Inline Search Form -->
                        <form method="get" action="{{ route('class.assignStudents', $class->id) }}" class="d-inline">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" name="student_name" class="form-control float-right"
                                    placeholder="Search Student Name" value="{{ Request::get('student_name') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('class.assignStudents', $class->id) }}" class="btn btn-default">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <form method="post" action="{{ route('class.assignStudents.post', $class->id) }}">
                    @csrf
                    <!-- Student Selection Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Student Name</th>
                                        <th class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <label class="mr-2 mb-0 font-weight-bold">Select All</label>
                                                <input type="checkbox" id="select-all" style="transform: scale(1.3);">
                                            </div>
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                    {{ $class->students->contains($student->id) ? 'checked disabled' :
                                                '' }}
                                                style="transform: scale(1.2);">
                                                @if ($class->students->contains($student->id))
                                                <input type="hidden" name="already_assigned_students[]"
                                                    value="{{ $student->id }}">
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Small Note -->
                            <div class="mt-2 text-end" style="padding-right: 10px;">
                                <small class="text-muted">
                                    <em>Note: A student can only be assigned to one class per academic year.</em>
                                </small>
                            </div>
                            @if ($errors->has('student_ids'))
                            <span class="text-danger">{{ $errors->first('student_ids') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Pagination and Submit Button -->
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <!-- Pagination Links -->
                        <div>
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                        <!-- Action Buttons and Note -->
                        <div class="ml-auto d-flex align-items-center">
                            <button type="submit" class="btn btn-primary btn-sm">Assign Students</button>
                        </div>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedStudents as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>
                                        <form method="post"
                                            action="{{ route('class.removeStudent', ['classId' => $class->id, 'studentId' => $student->id]) }}"
                                            onsubmit="return confirm('Are you sure you want to remove this student from the class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
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

<!-- Select All Script -->
<script>
    document.getElementById('select-all').addEventListener('click', function(event) {
        let checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    });
</script>

@endsection
