@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                {{-- <!-- Back Button -->
                <div class="col-auto">
                    <a href="{{ route('teacher.list', $teacher->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div> --}}

                <!-- Page Title -->
                <div class="col">
                    {{-- <h1 class="m-0">All Class (TEACHING SUBJECT) For <strong>{{ $teacher->name }}</strong></h1>
                    --}}
                    <h1 class="m-0">Teaching Assignments for <strong>{{ $teacher->name }}</strong></h1>
                </div>

                <!-- Action Buttons -->
                <div class="col-auto text-right">
                    <a href="{{ route('teacher.assignClass', $teacher->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Assign New Subject Class
                    </a>
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
                <a href="{{ route('teacher.list') }}">Teacher List </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.classAssignments', $teacher->id)}}"> Teaching Assignment </a>
            </li>
        </ol>
    </nav>

    <!-- Filters Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Assignments</h3>
                </div>
                <!-- Form Start -->
                <form method="get" action="{{ route('teacher.classAssignments', $teacher->id) }}">
                    <div class="card-body">
                        <div class="row">
                            {{-- Academic Year --}}
                            <div class="form-group col-md-3">
                                <label>Academic Year (First)</label>
                                <select id="academic_year_id" name="academic_year_id" class="form-control" required>
                                    <option value="">-- Select Year --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id')==$year->id ?
                                        'selected' : '' }}>
                                        {{ $year->academic_year_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Class Dropdown -->
                            <div class="form-group col-md-3">
                                <label>Class</label>
                                <select id="class_id" name="class_id" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id ?
                                        'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Buttons -->
                            <div class="form-group col-md-3">
                                <button class="btn btn-success" type="submit" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('teacher.classAssignments', $teacher->id) }}" class="btn btn-warning"
                                    style="margin-top: 30px">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> <strong>Assigned Classes</strong> </h3>
                </div>
                <div class="card-body">
                    @if($classAssignments->isEmpty())
                    <p>No classes assigned to this teacher yet.</p>
                    @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Grade Level</th>
                                <th>Subject</th>
                                <th>Syllabus</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classAssignments as $index => $assignment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $assignment->academicYear->academic_year_name }}</td>
                                <td>{{ $assignment->class->name }}</td>
                                <td>{{ $assignment->gradeLevel->grade_name }}</td>
                                <td>{{ $assignment->subject->subject_name }}</td>
                                <td>{{ $assignment->syllabus->syllabus_name }}</td>
                                <td>
                                    <!-- Add actions if needed -->
                                    <form action="{{ route('teacher.deleteAssignment', $assignment->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete Class</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    document.getElementById('academic_year_id').addEventListener('change', function () {
    const academicYearId = this.value;
    const classDropdown = document.getElementById('class_id');
    classDropdown.innerHTML = '<option>Loading...</option>';

    if (academicYearId) {
        fetch("{{ route('teacher.getClasses') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ academic_year_id: academicYearId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch classes');
            }
            return response.json();
        })
        .then(data => {
            classDropdown.innerHTML = '<option value="">-- Select Class --</option>';
            data.forEach(classItem => {
                const option = document.createElement('option');
                option.value = classItem.id;
                option.textContent = classItem.name;
                classDropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error(error);
            classDropdown.innerHTML = '<option value="">Failed to load classes</option>';
        });
    }
});
</script>
@endsection
