@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header bg-cyan">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Students Below 61% - <strong> {{ $selectedAcademicYear->academic_year_name ?? '' }} -
                            {{ $class->name ??
                            'N/A'
                            }}</strong> </h1>

                </div>
            </div>
        </div>
    </section>

    {{-- breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.dashboard') }}">Home </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('teacher.analytic.reportStudentLess60Percent', ['yearId' => $currentAcademicYear->id]) }}">Report < 61%
                    Performance</a>
            </li>
        </ol>
    </nav>

    <!-- Filters Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Students</h3>
                </div>
                <form method="get"
                    action="{{ route('teacher.analytic.reportStudentLess60Percent', ['yearId' => $selectedAcademicYear->id]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Exam Type</label>
                                <select class="form-control" name="exam_type_id">
                                    <option value="">-- Select Exam Type --</option>
                                    @foreach($examTypes as $examType)
                                    <option value="{{ $examType->id }}" {{ request('exam_type_id')==$examType->id ?
                                        'selected' : '' }}>
                                        {{ $examType->exam_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
                                <button type="submit" class="btn btn-primary" style="margin-top: 30px">Filter</button>
                                <a href="{{ route('teacher.analytic.reportStudentLess60Percent', ['yearId' => $currentAcademicYear->id ?? '']) }}"
                                    class="btn btn-success" style="margin-top: 30px">Reset</a>
                                <button id="saveImage" type="button" class="btn btn-info" style="margin-top: 30px">Save
                                    as Image</button>
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
                    <h3 class="card-title">Students Below 61%</h3>
                </div>
                <div class="card-body">
                    @if($data->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Percentage</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Failed Subjects</th>
                                <th>Absent Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->class_name }}</td>
                                <td class="{{ $student->percentage < 40 ? 'text-danger' : '' }}">{{
                                    round($student->percentage, 2) }}%</td>
                                {{-- <td>{{ $student->failed_subjects ?: 'None' }}</td>
                                <td>{{ $student->absent_subjects ?: 'None' }}</td> --}}
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
                    <p class="text-center text-danger">No students below 61% for the selected filters.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
