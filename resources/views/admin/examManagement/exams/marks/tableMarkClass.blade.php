@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Marks for {{ $examType->exam_type_name }} - {{ $syllabus->syllabus_name }}
                        ({{ $year->academic_year_name }}) : {{ $class->name }}</h1>
                </div>
            </div>
            <a>Data Exam / {{ $year->academic_year_name }} / {{ $examType->exam_type_name }} /
                {{ $syllabus->syllabus_name }} / {{ $class->name }}</a>
        </div>
    </section>

    <!-- Individual Marks Table -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            @foreach ($subjects as $subject)
                            <th>{{ $subject->subject_name }}</th>
                            @endforeach
                            <th>Attendance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->full_name }}</td>

                            @foreach ($subjects as $subject)
                            @php
                                $studentMark = $marks->get($student->id)?->firstWhere('subject_id', $subject->subject_id);
                                $markValue = $studentMark->mark ?? 'N/A';
                            @endphp
                            <td class="{{ is_numeric($markValue) ? ($markValue >= 80 ? 'text-success' : ($markValue < 40 ? 'text-danger' : '')) : '' }}">
                                {{ $markValue }}
                            </td>
                            @endforeach

                            @php
                                $summary = $studentsSummary->firstWhere('student_id', $student->id);
                            @endphp
                            <td>{{ $summary->attendance ?? '0' }} days</td>
                            <td>
                                <a href="{{ route('exams.marks.studentReport', [$year->id, $examType->id, $syllabus->id, $class->id, $student->id]) }}"
                                    class="btn btn-primary btn-sm" target="_blank">View Report</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Right-aligned button for editing -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('exams.marks.edit', [$year->id, $examType->id, $syllabus->id, $class->id]) }}"
                        class="btn btn-primary">Edit Marks</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Summary Table -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Total Grade</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Position in Class</th>
                            <th>Position in Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentsSummary as $index => $summary)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $summary->student->full_name }}</td>
                            <td>{{ $summary->total_grade ?? 'N/A' }}</td>
                            <td>{{ $summary->total_marks ?? 'N/A' }}</td>
                            <td>{{ $summary->percentage ?? 'N/A' }}%</td>
                            <td>{{ $summary->position_in_class ?? 'N/A' }}</td>
                            <td>{{ $summary->position_in_grade ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>
@endsection
