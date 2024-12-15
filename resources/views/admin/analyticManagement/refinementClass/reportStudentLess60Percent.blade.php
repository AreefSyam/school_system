@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelas Pemulihan - Students Below 61%</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filters</h3>
                </div>
                <form method="get" action="{{ route('analytic.reportStudentLess60Percent') }}">
                    <div class="card-body">
                        <div class="row">
                            <!-- Academic Year -->
                            <div class="form-group col-md-2">
                                <label>Academic Year</label>
                                <select class="form-control" name="academic_year_id">
                                    <option value="">-- Select Year --</option>
                                    @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id')==$year->id ?
                                        'selected' : '' }}>
                                        {{ $year->academic_year_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Class -->
                            <div class="form-group col-md-2">
                                <label>Class</label>
                                <select class="form-control" name="class_id">
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id ? 'selected' :
                                        '' }}>
                                        {{ $class->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Exam Type -->
                            <div class="form-group col-md-2">
                                <label>Exam Type</label>
                                <select class="form-control" name="exam_type_id">
                                    <option value="">-- Select Exam --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Syllabus -->
                            <div class="form-group col-md-2">
                                <label>Syllabus</label>
                                <select class="form-control" name="syllabus_id">
                                    <option value="">-- Select Syllabus --</option>
                                    @foreach($syllabuses as $syllabus)
                                    <option value="{{ $syllabus->id }}" {{ request('syllabus_id')==$syllabus->id ?
                                        'selected' : '' }}>
                                        {{ $syllabus->syllabus_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Button -->
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('analytic.reportStudentLess60Percent') }}" class="btn btn-success"
                                    style="margin-top: 30px">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Students Table -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students Needing Kelas Pemulihan</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Year</th>
                                <th>Class</th>
                                <th>Percentage</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Failed Subjects</th>
                                <th>Absent Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->academic_year_name }}</td>
                                <td>{{ $student->class_name }}</td>
                                <td>
                                    <span
                                        class="@if($student->percentage < 40) text-danger @elseif($student->percentage > 79) text-success @endif">
                                        {{ round($student->percentage, 2) }}%
                                    </span>
                                </td>
                                <td>{{ $student->total_marks }}</td>
                                <td>{{ $student->total_grade ?? 'N/A' }}</td>
                                <td class="{{ $student->failed_subjects ? 'text-danger' : 'text-dark' }}">
                                    {{ $student->failed_subjects ? implode(', ', explode(',',
                                    $student->failed_subjects)) : 'None' }}
                                </td>
                                <td class="{{ $student->absent_subjects ? 'text-warning' : 'text-dark' }}">
                                    {{ $student->absent_subjects ? implode(', ', explode(',',
                                    $student->absent_subjects)) : 'None' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">
                        No students require Kelas Pemulihan at this time. Please apply filters to refine your search or
                        verify the selected criteria.
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
