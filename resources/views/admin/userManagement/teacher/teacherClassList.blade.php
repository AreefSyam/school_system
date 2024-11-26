@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <!-- Back Button -->
                <div class="col-auto">
                    <a href="{{ route('teacher.list', $teacher->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <!-- Page Title -->
                <div class="col">
                    <h1 class="m-0">All Class Assignments for <strong>{{ $teacher->name }}</strong></h1>
                </div>

                <!-- Action Buttons -->
                <div class="col-auto text-right">
                    <a href="{{ route('teacher.assignClass', $teacher->id) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Assign New Class
                    </a>
                </div>
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
@endsection
